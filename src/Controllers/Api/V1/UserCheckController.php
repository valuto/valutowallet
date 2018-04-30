<?php

namespace Controllers\Api\V1;

use Psr\Http\Message\ServerRequestInterface;
use Controllers\Controller;
use Models\User;

class UserCheckController extends Controller
{
    /**
     * Check if user exists.
     * 
     * @return 
     */
    public function show(ServerRequestInterface $request)
    {
        global $mysqli;

        $params = $request->getQueryParams();

        $user   = new User($mysqli);
        $result = $user->getUserByUsername($params['username']);

        return json_encode([
            'exists' => (bool)$result,
        ]);
    }
}