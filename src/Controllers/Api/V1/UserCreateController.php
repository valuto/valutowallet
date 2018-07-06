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
     * @todo Implement data repositories and move insertion of user to the repository.
     * @return 
     */
    public function store(ServerRequestInterface $request)
    {
        global $mysqli;

        $params = $request->getParsedBody();
        $particulars = $params['particulars'];

        $stmt = $mysqli->prepare("INSERT INTO users (
            `date`, 
            `ip`, 
            `username`, 
            `email`,
            `password`, 
            `supportpin`, 
            `uses_old_account_identifier`,
            `first_name`,
            `last_name`,
            `set_password_token`,
            `set_password_before`,
            `origin`,
            `bounty_signup`
        ) 
        VALUES (
            \"" . date("n/j/Y g:i a") . "\", 
            ?,
            ?,
            ?,
            NULL,
            \"". rand(10000,99999) . "\", 
            \"0\",
            ?,
            ?,
            ?,
            DATE_ADD(NOW(), INTERVAL 2 DAY),
            \"api\",
            ?
        );");

        if (!$stmt) {
            throw new \Exception('Could not create user.');
        }

        $setPasswordToken = base64_encode(random_bytes(30));
        $bounty           = isset($params['bounty']) ? (int)$params['bounty'] : 0;

        $stmt->bind_param('ssssssi', $params['client_ip'], $particulars['email'], $particulars['username'], $particulars['first_name'], $particulars['last_name'], $setPasswordToken, $bounty);
        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            return json_encode([
                'success' => true,
                'user_id' => $mysqli->insert_id,
                'setPasswordToken' => $setPasswordToken,
            ]);
        } else {
            return json_encode([
                'error'   => 'user_creation_failed',
                'message' => $mysqli->error,
            ]);
        }

    }
}