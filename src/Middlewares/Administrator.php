<?php

namespace Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Administrator
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
        if (empty($_SESSION['user_admin']) || $_SESSION['user_admin'] != 1) {
            throw new \Exception('You are not authenticated as administrator.');
        }

        // Pass the request and response on to the next responder in the chain
        return $next($request, $response);
    }
    
}