<?php

namespace Controllers\Api\V1\Auth;

use Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;
use Psr\Http\Message\ServerRequestInterface;
use Controllers\Api\V1\UserApiController;
use Models\User;

class UserTwofactorController extends UserApiController
{
    /**
     * Is two factor enabled for user?
     * 
     * @param ServerRequestInterface  $request  the request object.
     * @return 
     */
    public function enabled(ServerRequestInterface $request)
    {
        $this->validateUserID($request);

        $user = $this->user->getUserById($this->userId);
        
        return json_encode([
            'status' => 'success',
            'enabled' => (bool)$user['authused']
        ]);
    }

    /**
     * Check if user exists.
     * 
     * @param ServerRequestInterface  $request  the request object.
     * @return 
     */
    public function verify(ServerRequestInterface $request)
    {
        $this->validateUserID($request);
        
        $params = $request->getParsedBody();
        $user   = $this->user->getUserById($this->userId);
        
        try {
            $oneCode = $this->user->get2faOneCode($user, $params['password']);
        } catch (WrongKeyOrModifiedCiphertextException $ex) {
            return json_encode([
                'status'  => 'error',
                'error'   => 'login_incorrect',
                'message' => 'The login used to retrieve Google Authenticator secret was not recognised.',
            ]);
        }

        if ($oneCode === $params['code']) {
            return json('status', 'success');
        } else {
            return json_encode([
                'status'  => 'error',
                'error'   => 'invalid_code',
                'message' => 'Codes doesn\'t match.',
            ]);
        }
    }
}