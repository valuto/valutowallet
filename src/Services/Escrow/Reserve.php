<?php

namespace Services\Escrow;

use Factories\ClientFactory;
use Models\User;
use Models\Reservation;
use Models\ReservationTransaction;
use Services\ValutoDaemon\Transaction;
use Exception;
use Exceptions\InsufficientFundsException;
use Exceptions\AccessDeniedException;
use Exceptions\ReservationAlreadyReleasedException;

class Reserve
{
    /**
     * The database instance.
     * 
     * @var MySQLi
     */
    protected $mysqli;

    /**
     * RPC client instance.
     * 
     * @var Services\ValutoDaemon\Client
     */
    protected $clientPayer;

    /**
     * RPC client instance.
     * 
     * @var Services\ValutoDaemon\Client
     */
    protected $clientReceiver;

    /**
     * Transaction service instance.
     * 
     * @var Services\ValutoDaemon\Transaction
     */
    protected $transaction;

    /**
     * User model instance.
     * 
     * @var Models\User
     */
    protected $user;

    /**
     * Reservation model instance.
     * 
     * @var Models\Reservation
     */
    protected $reservation;

    /**
     * Reservation transaction model instance.
     * 
     * @var Models\ReservationTransaction
     */
    protected $reservationTransaction;

    /**
     * Construct service with dependencies.
     * 
     * @param  Client $client
     * @return void
     */
    public function __construct()
    {
        global $mysqli;

        $this->mysqli = $mysqli;
        $this->user   = new User($this->mysqli);
        $this->reservation = new Reservation($this->mysqli);
        $this->reservationTransaction = new ReservationTransaction($this->mysqli);
        $this->clientReceiver = ClientFactory::build();
        $this->client = ClientFactory::build();
        $this->transaction = new Transaction($this->mysqli);
    }

    /**
     * Save reservation in database.
     * 
     * @param  int     $userId
     * @param  string  $origin
     * @param  string  $referenceId
     * @param  decimal $amount
     * @param  string  $state
     * @return boolean
     */
    public function save($userId, $origin, $referenceId, $amount, $state)
    {
        $this->reservation->create([
            'user_id' => $userId,
            'origin' => $origin,
            'reference_id' => $referenceId,
            'amount' => $amount,
            'state' => $state,
        ]);

        $reservationId = $this->mysqli->insert_id;

        return $reservationId;
    }

    /**
     * Update reservation in database.
     * 
     * @param  int     $reservationId
     * @param  string  $state
     * @return boolean
     */
    public function update($reservationId, $state)
    {
        $this->reservation->update([
            'user_id' => $reservationId,
            'state' => $state,
        ]);

        $reservationId = $this->mysqli->insert_id;

        return $reservationId;
    }


    /**
     * Save transaction details in database.
     * 
     * @param  int     $reservationId
     * @param  string  $transactionId
     * @param  string  $action
     * @return boolean
     */
    public function attachTransaction($reservationId, $transactionId, $action)
    {
        $this->reservationTransaction->create([
            'reservation_id' => $reservationId,
            'transaction_id' => $transactionId,
            'action' => $action,
        ]);

        $reservationTransactionId = $this->mysqli->insert_id;

        return $reservationTransactionId;
    }

    /**
     * Withdraw order amount from user account and move to escrow.
     * 
     * @param  array  $payer
     * @param  float  $amount
     * @param  string $referenceId
     * @return boolean
     */
    public function toEscrow($payer, $amount, $referenceId)
    {
        $escrowUser = $this->getEscrowUser();
        $address = $this->getReceivingAddress($escrowUser);
        
        $this->client->setUser($payer);
        $this->checkBalance($amount);

        // Withdraw from wallet.
        list($valutoTransactionId, $transactionId) = $this->transaction->setClient($this->client)->withdraw($address, $amount);
        
        // Persist transaction and reservation details to database.
        $reservationId = $this->save($payer['id'], __CLASS__, $referenceId, $amount, 'in_transfer');
        $transactionId = $this->attachTransaction($reservationId, $transactionId, 'from_user_to_escrow_initiated');

        return [
            $valutoTransactionId,
            $reservationId,
        ];
    }

    /**
     * Release a reserved payment.
     * 
     * @param 
     */
    public function release($reservationId)
    {
        $reservation = $this->reservation->find($reservationId);
        $user = $this->user->getUserById($reservation['user_id']);

        // @TODO check reservation user id is same as OAuth user id (or oauth is system auth)
        // throw AccessDeniedException if not.

        if ($reservation['state'] === 'released') {
            throw new ReservationAlreadyReleasedException('The reservation has already been released.');
        }

        $address = $this->getReceivingAddress($user);
        $escrowUser = $this->getEscrowUser();

        $this->client->setUser($escrowUser);

        // Withdraw from wallet.
        list($valutoTransactionId, $transactionId) = $this->transaction->setClient($this->client)->withdraw($address, $reservation['amount']);
        
        // Update state of reservation.
        $reservationId = $this->update($reservationId, 'released');
        $transactionId = $this->attachTransaction($reservationId, $transactionId, 'released');

        return [
            $valutoTransactionId,
            $reservationId,
        ];
    }

    /**
     * Get the escrow user.
     * 
     * @return array
     */
    public function getEscrowUser()
    {        
        $escrowAccount = env('API_ESCROW_USER_ID', false);

        if ( ! $escrowAccount) {
            throw new Exception('Escrow account not specified.');
        }

        return $this->user->getUserById($escrowAccount);
    }
    
    /**
     * Check that user has sufficient funds.
     * 
     * @param decimal $amount
     * @return void
     * @throws InsufficientFundsException
     */
    protected function checkBalance($amount)
    {
        $noresbal   = $this->client->getBalance();
        $resbalance = $this->client->getBalance() - config('app', 'reserve');

        if ($amount > $resbalance) {
            throw new InsufficientFundsException('The user doesn\'t have enough funds to complete the withdrawal.');
        }
    }

    /**
     * Get receiving address for user.
     * 
     * @param  array  $escrowUser  the receiving user.
     * @return string
     */
    protected function getReceivingAddress($receiver)
    {
        $address = $this->clientReceiver->setUser($receiver)->getNewAddress();

        if (empty($address)) {
            throw new Exception('New address could not be created for receiving user.');
        }

        return $address;
    }

}