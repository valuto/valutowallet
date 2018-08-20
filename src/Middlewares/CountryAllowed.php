<?php

namespace Middlewares;

use Models\HttpClient;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Services\GeoIp\Nekudo;

class CountryAllowed
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
        try {
            $lookup = Nekudo::lookup($_SERVER['REMOTE_ADDR']);
        } catch (\Exception $e) {
            // Lookup failed, skip country check and continue to next request.
            return $next($request, $response);
        }

        $blocked = config('app', 'blocked_countries');

        if (in_array($lookup->country->code, $blocked)) {
            redirect('/country-blocked');
        }

        // Pass the request and response on to the next responder in the chain
        return $next($request, $response);
    }
    
}