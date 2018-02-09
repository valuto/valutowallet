<?php

namespace Router;

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
     * @param Middleware $middlewar
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
     * Resolve route
     * 
     * @return void
     */
    public function resolve()
    {
        if ($this->currentAction()) {

            // Possible extend: set headers etc. here

            echo $this->callControllerMethod($this->currentAction());

        } else {

            http_response_code(404);
            
        }

        exit;
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
     * @param string $action
     */
    protected function callControllerMethod($action)
    {
        list($class, $method) = explode('@', $action);

        $this->request->parseParameters();

        return (new $class)->$method();
    }

    /**
     * Get current requested action
     * 
     * @return string action
     */
    protected function currentAction()
    {
        foreach ((array) self::$routes[$this->request->method()] as $route) {
            if ($this->request->path() === $route['uri'] && $this->middleware->check($route['middlewares'])) {
                return $route['action'];
            }
        }

        return false;
    }
}