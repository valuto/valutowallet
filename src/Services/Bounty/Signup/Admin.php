<?php

namespace Services\Bounty\Signup;

use Factories\ClientFactory;
use Models\Flash;
use Models\User;

class Admin
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
     * Update user with paid status.
     * 
     * @param  int     $userId
     * @return boolean
     */
    public function updateUser($userId)
    {
        $stmt = $this->mysqli->prepare("UPDATE users SET bounty_received_at=NOW() WHERE id=?");

        if ( ! $stmt) {
            throw new \Exception('Could not update bounty status on user');
        }

        $stmt->bind_param('i', $userId);
        $result = $stmt->execute();
        $stmt->close();

        if ( ! $result) {
            throw new \Exception('Could not update bounty status on user ' . $stmt->error);
        } else {
            return $result;
        }
    }

    /**
     * Prepare new user account for bounty.
     * 
     * @param  array   $receiver
     * @return boolean
     */
    public function payout($receiver)
    {
        $payer = $this->getPayer();
        $address = $this->getReceivingAddress($receiver);
        $amount = (float)config('bounty', 'payout_amount');

        $this->client->setUser($payer)->withdraw($address, $amount);

        return true;
    }

    /**
     * Get receiving address from user.
     * 
     * @param  array  $receiver the receiving user.
     * @return string
     */
    protected function getReceivingAddress($receiver)
    {
        $userAddresses = $this->client->setUser($receiver)->getAddressList();

        if (empty($userAddresses) || ! is_array($userAddresses)) {
            throw new \Exception('Receiver has no addresses.');
        }

        return $userAddresses[0];
    }

    /**
     * Get the bounty-paying user.
     * 
     * @return array
     */
    protected function getPayer()
    {
        $payFromUserId = config('bounty', 'pay_from_user_id');

        if ( ! $payFromUserId) {
            throw new \Exception('No bounty payment sender user was set.');
        }

        return $this->user->adminGetUserInfo($payFromUserId);
    }
}