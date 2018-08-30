<?php

namespace Controllers\Api\V1\Payment;

use Psr\Http\Message\ServerRequestInterface;
use Controllers\Api\V1\UserApiController;
use Services\Payment\Reserve;
use Models\User;

use Exception;
use Exceptions\AccessDeniedException;
use Exceptions\ReservationAlreadyCapturedException;
use Exceptions\ReservationNotFoundException;
use Exceptions\ReservationNotCapturableStateException;

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

        $params = $request->getParsedBody();
        $reservationId  = $params['reservation_id'];

        $escrowUser = $this->reserve->getEscrowUser();

        try {

            list($valutoTransactionId, $amount, $state) = $this->reserve->capture($reservationId);

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
                'message' => 'The reservation has already been captured.',
            ]);

        } catch (ReservationNotCapturableStateException $e) {
            
            return json_encode([
                'status' => 'error',
                'error' => 'reservation_not_capturable',
                'message' => 'The reservation is not in a capturable state.',
            ]);

        } catch (Exception $e) {

            return json_encode([
                'status' => 'error',
                'error' => 'release_error',
                'message' => 'Release of reservation failed unexpectedly.',
            ]);

        }

        return json_encode([
            'status' => 'success',
            'state' => $state,
            'transaction_id' => $valutoTransactionId,
            'amount' => $amount,
        ]);
    }
}