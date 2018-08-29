<?php

namespace Controllers\Api\V1\Payment;

use Psr\Http\Message\ServerRequestInterface;
use Controllers\Api\V1\UserApiController;
use Services\Escrow\Reserve;
use Models\User;
use Exception;

class CaptureController extends UserApiController
{
    /**
     * Capture reservation for order.
     * 
     * @param ServerRequestInterface  $request  the request object.
     * @return 
     */
    public function store(ServerRequestInterface $request)
    {
        // @TODO add scope check, so only vlumarketsystem user can perform this action.
        
        $this->reserve = new Reserve();

        $params   = $request->getParsedBody();
        $orderId  = $params['order_id'];
        
        $escrowUser = $this->reserve->getEscrowUser();

        // @TODO pay merchant from escrow account.
        // @TODO determine state of transfer.
        // @TODO move escrow to separate host.
        // @TODO save order id and transaction details in db.

        try {

            // Move amount from escrow to merchant.
            list($valutoTransactionId, $amount) = $this->reserve->capture($reservationId);

        } catch (ReservationNotFoundException $e) {
            
            return json_encode([
                'status' => 'error',
                'error' => 'reservation_not_found',
                'message' => 'The reservation could not be found.',
            ]);

        } catch (ReservationAlreadyCapturedException $e) {
            
            return json_encode([
                'status' => 'error',
                'error' => 'already_captured',
                'message' => 'The reservation for the order has already been captured.',
            ]);

        } catch (Exception $e) {

            return json_encode([
                'status' => 'error',
                'error' => 'release_error',
                'message' => 'Release of reservation failed unexpectedly.',
            ]);

        }

        $state = 'in_transfer';

        return json_encode([
            'status' => 'success',
            'state' => $state,
            'transaction_id' => $valutoTransactionId,
            'amount' => $amount,
        ]);
    }
}