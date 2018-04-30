<?php

namespace Controllers\Api\V1;

use Psr\Http\Message\ServerRequestInterface;
use Controllers\Controller;
use Models\User;

class UserCreateController extends Controller
{
    /**
     * Create new user.
     * 
     * @return 
     */
    public function store(ServerRequestInterface $request)
    {
        global $mysqli;

        $params = $request->getQueryParams();

        $user   = new User($mysqli);
        
        $query = $this->mysqli->query(
            "INSERT INTO users (
                `date`, 
                `ip`, 
                `username`, 
                `password`, 
                `supportpin`, 
                `uses_old_account_identifier`,
                `email`
            ) 
            VALUES (
                \"" . date("n/j/Y g:i a") . "\", 
                \"". $params['client_ip'] . "\", 
                \"" . $params['email'] ."\", 
                \"" . $params['username'] . "\", 
                \"". rand(10000,99999) . "\", 
                \"0\",
                \"" . $params['email'] ."\", 
            );");

        if ($query) {
            return json_encode([
                'success' => $this->mysqli->insert_id,
            ]);
        } else {
            return json_encode([
                'error' => '',
            ]);
        }

    }
}