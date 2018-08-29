<?php

namespace Controllers\Api\V1\Payment;

use Psr\Http\Message\ServerRequestInterface;
use Controllers\Api\V1\UserApiController;
use Services\Escrow\Reserve;
use Models\User;
use Exception;
use Exceptions\InsufficientFundsException;
use Exceptions\ReservationAlreadyReleasedException;
use Exceptions\ReservationAlreadyCapturedException;

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
        $referenceId = $params['reference_id'];
        
        try {

            // Move amount to escrow account.
            list($valutoTransactionId, $reservationId) = $this->reserve->toEscrow($user, $amount, $referenceId);

        } catch (InsufficientFundsException $e) {

            return json_encode([
                'status' => 'error',
                'error' => 'insufficient_funds',
                'message' => 'The account has insufficient funds',
            ]);

        } catch (Exception $e) {

            return json_encode([
                'status' => 'error',
                'error' => 'reservation_error',
                'message' => 'Reservation failed unexpectedly',
            ]);

        }

        $state = 'in_transfer';

        // @TODO move escrow to separate host.

        return json_encode([
            'status' => 'success',
            'state' => $state,
            'sender' => $user['id'],
            'transaction_id' => $valutoTransactionId,
            'amount' => $amount,
            'reservation_id' => $reservationId,
        ]);
    }

    /**
     * Release reserved amount from user.
     * 
     * @param ServerRequestInterface  $request  the request object.
     * @return 
     */
    public function destroy(ServerRequestInterface $request)
    {
        // @TODO add scope check, so only vlumarketsystem user can perform this action.

        $this->reserve = new Reserve();

        $params = $request->getParsedBody();
        $reservationId = $params['reservation_id'];

        try {

            // Move amount to escrow account.
            list($valutoTransactionId, $amount) = $this->reserve->release($reservationId);

        } catch (ReservationAlreadyReleasedException $e) {
            
            return json_encode([
                'status' => 'error',
                'error' => 'already_released',
                'message' => 'The reservation for the order has already been released',
            ]);

        } catch (ReservationAlreadyCapturedException $e) {
            
            return json_encode([
                'status' => 'error',
                'error' => 'already_captured',
                'message' => 'The reservation for the order has already been captured',
            ]);

        } catch (Exception $e) {

            return json_encode([
                'status' => 'error',
                'error' => 'release_error',
                'message' => 'Release of reservation failed unexpectedly',
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