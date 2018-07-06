<?php

namespace Controllers\Api\V1\Payment;

use Psr\Http\Message\ServerRequestInterface;
use Controllers\Api\V1\UserApiController;
use Services\Escrow\Reserve;
use Models\User;
use Exception;

class ReserveController extends UserApiController
{
    /**
     * Reserve amount from user.
     * 
     * @param ServerRequestInterface  $request  the request object.
     * @return 
     */
    public function store(ServerRequestInterface $request)
    {
        $this->reserve = new Reserve();

        $this->validateUserID($request);
        
        $user = $this->user->getUserById($this->userId);
        
        if ( ! $user) {
            return $this->userNotFound();
        }

        $params   = $request->getParsedBody();
        $amount   = $params['amount'];
        $receiver = $params['receiver'];
        $orderId  = $params['order_id'];
        
        $this->client->setUser($user);

        $noresbal   = $this->client->getBalance();
        $resbalance = $this->client->getBalance() - config('app', 'reserve');

        if ($amount > $resbalance) {
            return json_encode([
                'status' => 'error',
                'error' => 'insufficient_funds',
                'message' => 'The account has insufficient funds',
            ]);
        }
        
        $escrowUser = $this->reserve->getEscrowUser();

        // Move amount to escrow account.
        $transactionId = $this->reserve->withdrawFromUser($escrowUser, $user, $amount);

        // @TODO determine state of transfer.
        // @TODO move escrow to separate host.
        // @TODO save order id and transaction details in db.

        return json_encode([
            'status' => 'success',
            'state' => 'in_transfer',
            'sender' => $user['id'],
            'transaction_id' => $transactionId,
            'amount' => $amount,
        ]);
    }

    /**
     * Release order from user.
     * 
     * @param ServerRequestInterface  $request  the request object.
     * @return 
     */
    public function destroy(ServerRequestInterface $request)
    {
        // @TODO

        return json_encode([
            'status' => 'success',
            'state' => 'in_transfer',
            'transaction_id' => 'TBD',
            'amount' => '0.00',
        ]);
    }

}