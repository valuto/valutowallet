<?php

namespace Controllers\Api\V1;

use Psr\Http\Message\ServerRequestInterface;
use Controllers\Controller;
use Models\User;
use Exception;

class UserApiController extends Controller
{
    /**
     * The database instance.
     * 
     * @var MySQLi
     */
    protected $mysqli;

    /**
     * The user model instance.
     * 
     * @var User
     */
    protected $user;

    /**
     * The user ID in the access token.
     * 
     * @var int
     */
    protected $userId;

    /**
     * Construct controller with dependencies.
     * 
     * @return void
     */
    public function __construct()
    {
        global $mysqli;

        $this->mysqli = $mysqli;
        $this->user   = new User($this->mysqli);
    }
    
    /**
     * Validate user ID.
     * 
     * @param ServerRequestInterface  $request  the request object.
     * @return void
     */
    protected function validateUserID(ServerRequestInterface $request)
    {   
        $this->userId = $request->getAttribute('oauth_user_id');

        if (empty($this->userId)) {
            throw new Exception('User ID not found in access token.');
        }
    }
}