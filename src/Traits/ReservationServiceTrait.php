<?php

namespace Traits;

trait ReservationServiceTrait
{

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