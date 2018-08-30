<?php

namespace Services\Payment;

use Factories\ClientFactory;
use Models\User;
use Models\Reservation;
use Models\ReservationTransaction;
use Services\ValutoDaemon\Transaction;
use Traits\ReservationServiceTrait;

use Exception;

class Cashback
{
    use ReservationServiceTrait;

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
     * Pay out cashback to buyer.
     * 
     * @param array $reservation
     * @param float $percentage the cashback percentage of the reserved total amount.
     * @return array
     */
    public function pay($reservationId, $percentage)
    {
        // @TODO check if there already exists a cashback transaction related to this reservation.
        // throw exception if there does.

        $reservation = $this->reservation->find($reservationId);

        $amount = bcmul($reservation['amount'], $percentage, 8);

        list($valutoTransactionId, $transactionId) = $this->toUser($amount, $reservation['sender_user_id'], $reservationId);

        $state = 'in_transfer'; // @TODO

        return [
            $valutoTransactionId,
            $transactionId,
            $state,
            $amount,
        ];
    }

    /**
     * Withdraw from cashback account and transfer to user.
     * 
     * @param  float  $amount
     * @param  int $userId
     * @return array
     */
    public function toUser($amount, $userId, $reservationId)
    {
        // Receiving address.
        $receiver = $this->user->getUserById($userId);
        $address = $this->getReceivingAddress($receiver);
     
        // Set sender user.
        // @TODO use a different user so it doesn't go from escrow account.
        // Should go from same account as cut is transferred to.
        $escrowUser = $this->getEscrowUser();   
        $this->client->setUser($escrowUser);

        // Withdraw from wallet.
        list($valutoTransactionId, $transactionId) = $this->transaction->setClient($this->client)->withdraw($address, $amount);

        // Persist transaction and reservation details to database.
        $transactionId = $this->attachTransaction($reservationId, $transactionId, 'from_escrow_to_receiver_initiated');

        return [
            $valutoTransactionId,
            $transactionId,
        ];
    }

}