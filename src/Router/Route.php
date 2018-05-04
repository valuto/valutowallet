<?php

namespace Router;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Router\NoOperation;

class Route
{

    /**
     * @var array
     */
    public static $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => [],
    ];

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Middleware
     */
    protected $middleware;

    /**
     * @var string HTTP method
     */
    protected $method;

    /**
     * @var string
     */
    protected $uri;

    /**
     * @var string 
     */
    protected $action;

    /**
     * Constructor
     * 
     * @param Request $request
     * @param Middleware $middleware
     */
    public function __construct(Request $request = null, Middleware $middleware = null)
    {
        $this->request = $request ? $request : new Request;
        $this->middleware = $middleware ? $middleware : new Middleware;
    }

    /**
     * Get route
     * 
     * @param string $uri
     * @param string $action
     */
    public function get($uri, $action)
    {
        return $this->addRoute('GET', $uri, $action);
    }

    /**
     * Get route
     * 
     * @param string $uri
     * @param string $action
     */
    public function post($uri, $action)
    {
        return $this->addRoute('POST', $uri, $action);
    }

    /**
     * Get route
     * 
     * @param string $uri
     * @param string $action
     */
    public function put($uri, $action)
    {
        return $this->addRoute('PUT', $uri, $action);
    }

    /**
     * Get route
     * 
     * @param string $uri
     * @param string $action
     */
    public function delete($uri, $action)
    {
        return $this->addRoute('DELETE', $uri, $action);
    }

    /**
     * Add middlewares to route
     * 
     * @param array|string $names
     */
    public function middleware($names)
    {
        $middlewares = is_array($names) ? $names : \func_get_args();

        $this->addMiddleware($middlewares);
    }

    /**
     * If $condition is true continue chaining.
     * 
     * @param  boolean $condition
     * @return Route|NoOperation    Returns $this to continue chain when $conditition is true.
     *                              Returns NoOperation object otherwise, that will make every chained
     *                              method shoot with loose spit.
     */
    public function if($condition)
    {
        if ($condition) {
            return $this;
        } else {
            return new NoOperation();
        }
    }

    /**
     * Resolve route
     * 
     * @return void
     */
    public function resolve()
    {
        if ( ! isset(self::$routes[$this->request->method()])) {
            return false;
        }

        foreach ((array) self::$routes[$this->request->method()] as $route) {
            if ($this->request->path() === $route['uri']) {
                $response = $this->middleware->check($route['middlewares'], function(ServerRequestInterface $request, ResponseInterface $response) use ($route) {
                    return $this->callEndpoint($request, $response, $route);
                });
                return $this->respond($response);
            }
        }

        return false;
    }

    /**
     * Call the route endpoint at the end of the middleware call stack.
     * 
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $route
     */
    protected function callEndpoint(ServerRequestInterface $request, ResponseInterface $response, $route) {

        if ($route['action']) {
            $response->getBody()->write($this->callControllerMethod($route['action'], $request));
            return $response;
        } else {
            http_response_code(404);
            exit;
        }

    }

    /**
     * Register route
     * 
     * @param string $method HTTP method
     * @param string $uri
     * @param string $action 
     */
    protected function addRoute($method, $uri, $action)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->action = $action;

        self::$routes[$method][] = [
            'uri' => trim($uri, '/'),
            'action' => $action,
            'middlewares' => '',
        ];

        end(self::$routes[$method]);
        $this->routeKey = key(self::$routes[$method]);

        return $this;
    }

    /**
     * Register middleware
     * 
     * @param array $names
     */
    protected function addMiddleware($names)
    {
        self::$routes[$this->method][$this->routeKey]['middlewares'] = $names;

        return $this;
    }

    /**
     * Call controller method
     * 
     * @param string                 $action
     * @param ServerRequestInterface $request
     */
    protected function callControllerMethod($action, ServerRequestInterface $request)
    {
        list($class, $method) = explode('@', $action);

        $this->request->parseParameters();

        return (new $class)->$method($request);
    }
    
    /**
     * Send the response to the client
     *
     * @param ResponseInterface $response
     */
    protected function respond(ResponseInterface $response)
    {
        // Send response
        if (!headers_sent()) {
            // Headers
            foreach ($response->getHeaders() as $name => $values) {
                foreach ($values as $value) {
                    header(sprintf('%s: %s', $name, $value), false);
                }
            }
            // Set the status _after_ the headers, because of PHP's "helpful" behavior with location headers.
            // See https://github.com/slimphp/Slim/issues/1730
            // Status
            header(sprintf(
                'HTTP/%s %s %s',
                $response->getProtocolVersion(),
                $response->getStatusCode(),
                $response->getReasonPhrase()
            ));
        }
        // Body
        if (!$this->isEmptyResponse($response)) {
            $body = $response->getBody();
            if ($body->isSeekable()) {
                $body->rewind();
            }
            $contentLength  = $response->getHeaderLine('Content-Length');
            if (!$contentLength) {
                $contentLength = $body->getSize();
            }
            if (isset($contentLength)) {
                $amountToRead = $contentLength;
                while ($amountToRead > 0 && !$body->eof()) {
                    $data = $body->read(4096);
                    echo $data;
                    $amountToRead -= strlen($data);
                    if (connection_status() != CONNECTION_NORMAL) {
                        break;
                    }
                }
            } else {
                while (!$body->eof()) {
                    echo $body->read($chunkSize);
                    if (connection_status() != CONNECTION_NORMAL) {
                        break;
                    }
                }
            }
        }
    }
    
    /**
     * Helper method, which returns true if the provided response must not output a body and false
     * if the response could have a body.
     *
     * @see https://tools.ietf.org/html/rfc7231
     *
     * @param ResponseInterface $response
     * @return bool
     */
    protected function isEmptyResponse(ResponseInterface $response)
    {
        if (method_exists($response, 'isEmpty')) {
            return $response->isEmpty();
        }
        return in_array($response->getStatusCode(), [204, 205, 304]);
    }

}