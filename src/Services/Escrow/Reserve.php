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
use Exceptions\ReservationAlreadyCapturedException;
use Exceptions\ReservationNotFoundException;

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
    protected $client;

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
     * @param  int     $senderUserId
     * @param  int     $receiverUserId
     * @param  string  $origin
     * @param  string  $referenceId
     * @param  decimal $amount
     * @param  string  $state
     * @return boolean
     */
    public function save($senderUserId, $receiverUserId, $origin, $referenceId, $amount, $state)
    {
        $this->reservation->create([
            'sender_user_id' => $senderUserId,
            'receiver_user_id' => $receiverUserId,
            'origin' => $origin,
            'reference_id' => $referenceId,
            'amount' => $amount,
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
     * @param  array  $sender
     * @param  float  $amount
     * @param  string $referenceId
     * @param  int $receiverUserId
     * @return boolean
     */
    public function toEscrow($sender, $amount, $referenceId, $receiverUserId)
    {
        $escrowUser = $this->getEscrowUser();
        $address = $this->getReceivingAddress($escrowUser);
        
        $this->client->setUser($sender);
        $this->checkBalance($amount);

        // Withdraw from wallet.
        list($valutoTransactionId, $transactionId) = $this->transaction->setClient($this->client)->withdraw($address, $amount);
        
        $state = 'in_transfer';

        // Persist transaction and reservation details to database.
        $reservationId = $this->save($sender['id'], $receiverUserId, __CLASS__, $referenceId, $amount, $state);
        $transactionId = $this->attachTransaction($reservationId, $transactionId, 'from_user_to_escrow_initiated');

        return [
            $valutoTransactionId,
            $reservationId,
            $state,
        ];
    }

    /**
     * Release a reserved payment.
     * 
     * @param 
     */
    public function release($reservationId)
    {
        // @TODO check that vlumarketsystem oauth account was used.
        // throw AccessDeniedException if not.

        $reservation = $this->reservation->find($reservationId);

        if ( ! $reservation) {
            throw new ReservationNotFoundException('The reservation could not be found.');
        }

        $user = $this->user->getUserById($reservation['sender_user_id']);

        if ($reservation['state'] === 'released') {
            throw new ReservationAlreadyReleasedException('The reservation has already been released.');
        }

        if ($reservation['state'] === 'captured') {
            throw new ReservationAlreadyCapturedException('The reservation has already been captured.');
        }

        // @TODO update reservation status for reservation, i.e. check if it is still in_transfer or if it actually received in the (escrow) wallet account.
        // throw error if not completed yet, as escrow account should not pay something they have not received yet.

        $address = $this->getReceivingAddress($user);
        $escrowUser = $this->getEscrowUser();

        $this->client->setUser($escrowUser);

        // Withdraw from wallet.
        list($valutoTransactionId, $transactionId) = $this->transaction->setClient($this->client)->withdraw($address, $reservation['amount']);
        
        $state = 'released';

        $transactionId = $this->attachTransaction($reservationId, $transactionId, $state);

        // Update state of reservation.
        $this->reservation->updateState($reservationId, 'released');

        return [
            $valutoTransactionId,
            $reservation['amount'],
            $state,
        ];
    }


    /**
     * Capture a reserved payment.
     * 
     * @param 
     */
    public function capture($reservationId)
    {
        // @TODO check that vlumarketsystem oauth account was used.
        // throw AccessDeniedException if not.

        $reservation = $this->reservation->find($reservationId);

        if ( ! $reservation) {
            throw new ReservationNotFoundException('The reservation could not be found.');
        }

        $user = $this->user->getUserById($reservation['sender_user_id']);

        if ($reservation['state'] === 'released') {
            throw new ReservationAlreadyReleasedException('The reservation has already been released.');
        }

        if ($reservation['state'] === 'captured') {
            throw new ReservationAlreadyCapturedException('The reservation has already been captured.');
        }

        // @TODO update reservation status for reservation, i.e. check if it is still in_transfer or if it actually received in the (escrow) wallet account.
        // throw error if not completed yet, as escrow account should not pay something they have not received yet.

        $address = $this->getReceivingAddress($user);
        $escrowUser = $this->getEscrowUser();

        $this->client->setUser($escrowUser);

        // Withdraw from wallet.
        list($valutoTransactionId, $transactionId) = $this->transaction->setClient($this->client)->withdraw($address, $reservation['amount']);
        
        $transactionId = $this->attachTransaction($reservationId, $transactionId, 'released');

        // Update state of reservation.
        $this->reservation->updateState($reservationId, 'released');

        return [
            $valutoTransactionId,
            $reservation['amount'],
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