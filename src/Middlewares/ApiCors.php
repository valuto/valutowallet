<?php

namespace Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ApiCors
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
        $cors = config('api', 'cors_whitelist');

        if (!empty($cors)) {
            foreach ($cors as $origin) {
                header('Access-Control-Allow-Origin: ' . $origin, false);
            }
            header('Access-Control-Allow-Methods: GET, OPTIONS');
        }

        // Pass the request and response on to the next responder in the chain
        return $next($request, $response);
    }
    
}