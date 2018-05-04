<?php

namespace Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Models\Flash;

class CsrfToken
{

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable               $next
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $params = $request->getParsedBody();

        if ($params['token'] !== $_SESSION['token']) {

            // Generate new token.
            $_SESSION['token'] = sha1('@s%a$l£t#' . rand(0, 10000));

            // Show error message to user.
            return json_encode([
                'error' => lang('WALLET_TOKENS_DO_NOT_MATCH'),
                'newtoken' => $_SESSION['token'],
            ]);

        }

        // Pass the request and response on to the next responder in the chain
        return $next($request, $response);
    }
    
}