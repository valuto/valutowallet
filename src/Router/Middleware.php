<?php

namespace Router;

use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Middleware
{
    /**
     * Tip of the middleware call stack
     *
     * @var callable
     */
    protected $tip;
    
    /**
     * Check if middleware resolves positive
     * 
     * @param array    $middlewares
     * @param callback $endpoint
     * @return bool
     */
    public function check($middlewares, $endpoint)
    {
        $response = new Response();
        $request  = ServerRequest::fromGlobals();

        // Add endpoint callback at tip of call stack.
        $this->tip = $endpoint;

        $this->buildMiddlewareStack($middlewares);

        // Start call stack chain.
        return $this->callMiddlewareStack($request, $response);
    }

    /**
     * Build middleware stack.
     * 
     * @param array $middlewares
     */
    protected function buildMiddlewareStack($middlewares)
    {
        if (empty($middlewares)) {
            return false;
        }

        // Build middleware stack from bottom up.
        $middlewares = array_reverse($middlewares);

        foreach ($middlewares as $middleware) {

            $class = config('middleware', $middleware);

            if (!isset($class)) {
                throw new \Exception('Middleware not found');
            }

            $this->addToStack(new $class);

        }

    }

    /**
     * Add middleware to call stack.
     * 
     * @param  mixed $callable
     * @return void
     */
    protected function addToStack($callable)
    {
        $next = $this->tip;
        $this->tip = function (
            ServerRequestInterface $request,
            ResponseInterface $response
        ) use (
            $callable,
            $next
        ) {
            $result = call_user_func($callable, $request, $response, $next);

            if (is_bool($result) && $result === false) {
                return false;
            } elseif ($result instanceof ResponseInterface === false) {
                throw new \UnexpectedValueException(
                    'Middleware must return instance of \Psr\Http\Message\ResponseInterface or false'
                );
            }
            return $result;
        };
    }
    
    /**
     * Call middleware stack
     *
     * @param  ServerRequestInterface $request A request object
     * @param  ResponseInterface      $response A response object
     *
     * @return ResponseInterface
     */
    protected function callMiddlewareStack(ServerRequestInterface $request, ResponseInterface $response)
    {
        $start = $this->tip;
        $response = $start($request, $response);
        return $response;
    }

}