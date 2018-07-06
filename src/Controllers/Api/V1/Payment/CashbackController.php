<?php

namespace Controllers\Api\V1\Payment;

use Psr\Http\Message\ServerRequestInterface;
use Controllers\Controller;
use Services\Escrow\Reserve;
use Models\User;
use Exception;

class CashbackController extends Controller
{
    /**
     * Pay out cashback for order.
     * 
     * @param ServerRequestInterface  $request  the request object.
     * @return 
     */
    public function store(ServerRequestInterface $request)
    {
        // @TODO add scope check, so only vlumarketsystem user can perform this action.

        $this->reserve = new Reserve();

        $params     = $request->getParsedBody();
        $orderId    = $params['order_id'];
        $percentage = $params['percentage'];
        
        //$escrowUser = $this->reserve->getEscrowUser();

        // @TODO pay merchant from escrow account.
        // @TODO determine state of transfer.
        // @TODO move escrow to separate host.
        // @TODO save order id and transaction details in db.

        return json_encode([
            'status' => 'success',
            'state' => 'in_transfer',
            'transaction_id' => 'TBD',
            'amount' => '0.00',
        ]);
    }
}