<?php

namespace Middlewares;


use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\ResourceServer;
use Repositories\Authentication\AccessTokenRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ApiAuthenticate
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
        // IP whitelist.
        if (!in_array($_SERVER['REMOTE_ADDR'], config('api', 'ip_whitelist'))) {
            throw new \Exception('Host not allowed.');
        }

        $server = new ResourceServer(
            new AccessTokenRepository(),            // instance of AccessTokenRepositoryInterface
            config('api', 'public_key_path')  // the authorization server's public key
        );

        $middleware = new \League\OAuth2\Server\Middleware\ResourceServerMiddleware($server);

        return $middleware($request, $response, function($request, $response) use ($next) {

            // Pass the request and response on to the next responder in the chain
            return $next($request, $response);

        });
    }
    
}