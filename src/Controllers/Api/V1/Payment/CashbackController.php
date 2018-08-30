<?php

namespace Controllers\Api\V1\Payment;

use Psr\Http\Message\ServerRequestInterface;
use Controllers\Controller;
use Services\Payment\Cashback;
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

        $this->cashback = new Cashback();

        $params = $request->getParsedBody();
        $reservationId = $params['reservation_id'];
        $percentage = $params['percentage'];

        try {
            
            list($valutoTransactionId, $transactionId, $state, $amount) = $this->cashback->pay($reservationId, $percentage);

        } catch (Exception $e) {

            return json_encode([
                'status' => 'error',
                'error' => 'cashback_error',
                'message' => 'Cashback payment failed unexpectedly',
            ]);

        }

        return json_encode([
            'status' => 'success',
            'state' => $state,
            'transaction_id' => $transactionId,
            'amount' => $amount,
        ]);
    }
}