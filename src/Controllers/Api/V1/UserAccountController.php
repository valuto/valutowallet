<?php

namespace Controllers\Api\V1;

use Psr\Http\Message\ServerRequestInterface;
use Controllers\Controller;
use Services\ValutoDaemon\Client;
use Models\User;

class UserAccountController extends UserApiController
{
    /**
     * Get user account.
     * 
     * @param ServerRequestInterface  $request  the request object.
     * @return 
     */
    public function show(ServerRequestInterface $request)
    {
        $this->validateUserID($request);
        
        $user = $this->user->getUserById($this->userId);
        
        if ( ! $user) {
            return $this->userNotFound();
        }

        $this->client->setUser($user);

        $noresbal   = $this->client->getBalance();
        $resbalance = $this->client->getBalance() - config('app', 'reserve');
        $reserved   = 0; // @TODO - reserved in escrow account.

        return json_encode([
            'status' => 'success',
            'balance' => $resbalance + $reserved,
            'reserved' => $reserved,
            'available' => $resbalance,
        ]);
    }

}