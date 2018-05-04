<?php

namespace Services\Bounty\Signup;

use Factories\ClientFactory;
use Models\Flash;

class User
{
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
        $this->client = ClientFactory::build();
    }

    /**
     * Prepare new user account for bounty.
     * 
     * @param  array $user
     * @return boolean
     */
    public function prepareForBountyPayout($user)
    {
        if ( ! is_null($user['bounty_received_at'])) {
            return false;
        }

        if ((int)$user['bounty_signup'] === 1) {
            $this->client->getnewaddress();
        }

        return true;
    }

    
    /**
     * Show notice to user that the bounty is pending.
     * 
     * @param  array $user
     * @return boolean
     */
    public function showBountyPending($user)
    {
        if ( ! is_null($user['bounty_received_at'])) {
            return false;
        }

        if ((int)$user['bounty_signup'] === 1) {
            Flash::save('showNotice', lang('WALLET_NOTICE_BOUNTY_PENDING'));
        }

        return true;
    }
}