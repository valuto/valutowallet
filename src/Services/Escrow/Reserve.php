<?php

namespace Services\Escrow;

use Factories\ClientFactory;
use Models\User;

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
        $this->client = ClientFactory::build();
    }

    /**
     * Save reservation in database.
     * 
     * @param  int     $userId
     * @return boolean
     */
    public function save($userId)
    {
        // @TODO
    }

    /**
     * Prepare new user account for bounty.
     * 
     * @param  array  $escrowUser  the receiving user.
     * @param  array  $payer
     * @param  float  $amount
     * @return boolean
     */
    public function withdrawFromUser($escrowUser, $payer, $amount)
    {
        $address = $this->netReceivingAddress($escrowUser);

        return $this->client->setUser($payer)->withdraw($address, $amount);
    }

    /**
     * Get the escrow user.
     * 
     * @return array
     */
    public function getEscrowUser()
    {        
        $escrowAccount = env('API_VLUMARKET_ESCROW_USER_ID', false);

        if ( ! $escrowAccount) {
            throw new Exception('Escrow account not specified.');
        }

        return $this->user->getUserById($escrowAccount);
    }
    
    /**
     * Get escrow account receiving address.
     * 
     * @param  array  $escrowUser  the receiving user.
     * @return string
     */
    protected function netReceivingAddress($escrowUser)
    {
        $address = $this->client->setUser($escrowUser)->getNewAddress();

        if (empty($address)) {
            throw new \Exception('New address could not be created for escrow user.');
        }

        return $address;
    }

}