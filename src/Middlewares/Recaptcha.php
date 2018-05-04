<?php

namespace Middlewares;

use Models\HttpClient;
use Models\Flash;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Recaptcha
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
        $result = json_decode(HttpClient::post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => config('captcha', 'secret_key'),
            'response' => $_POST['g-recaptcha-response'],
            'remoteip' => $_SERVER['REMOTE_ADDR'],
        ]));

        if (!isset($result->success) || $result->success !== true) {
            Flash::save('error', 'Captcha do not match.');
            redirect('');
        }

        // Pass the request and response on to the next responder in the chain
        return $next($request, $response);

    }
    
}