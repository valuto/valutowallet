<?php

namespace Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Authenticate
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
        if (!empty($_SESSION['user_session'])) {

            if (empty($_SESSION['token'])) {
                $_SESSION['token'] = sha1('@s%a$l£t#' . rand(0, 10000));
            }

            // Pass the request and response on to the next responder in the chain
            return $next($request, $response);
        }

        throw new \Exception('You are not authenticated.');
    }
    
}