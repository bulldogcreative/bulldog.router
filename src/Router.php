<?php

namespace Bulldog;

use Bulldog\RouterInterface;
use Psr\Http\Message\ServerRequestInterface;

class Router implements RouterInterface
{
    private $routes = [];
    private $routeInfo = [];
    private $handler;
    private $vars;
    
    const HTTP_METHODS = [
        'GET',
        'POST',
        'PUT',
        'DELETE',
    ];
    
    public function addRoute($method, $uri, $callable)
    {
        $method = strtoupper($method);
        
        if (!in_array($method, self::HTTP_METHODS)) {
            throw new \InvalidArgumentException('Not a valid HTTP method.');
        }
        
        $this->routes[] = [
            'method' => $method,
            'url' => $uri,
            'callable' => $callable
        ];
    }
    
    public function get($uri, $callable)
    {
        $this->addRoute('GET', $uri, $callable);
    }
    
    public function post($uri, $callable)
    {
        $this->addRoute('POST', $uri, $callable);
    }
    
    public function delete($uri, $callable)
    {
        $this->addRoute('DELETE', $uri, $callable);
    }
    
    public function put($uri, $callable)
    {
        $this->addRoute('PUT', $uri, $callable);
    }
    
    public function run(ServerRequestInterface $request)
    {
        $dispatcher = $this->createSimpleDispatch();
        $httpMethod = $request->getMethod();
        $uri = $this->filterUri($request->getRequestTarget());
        
        $this->routeInfo = $dispatcher->dispatch($httpMethod, $uri);
        
        $this->handler = $this->routeInfo[1];
        $this->vars = $this->routeInfo[2];
        
        return $this;
    }
    
    public function routes()
    {
        return $this->routes;
    }
    
    public function handler()
    {
        return $this->handler;
    }
    
    public function vars()
    {
        return $this->vars;
    }
    
    public function routeInfo()
    {
        return $this->routeInfo;
    }
    
    private function createSimpleDispatch()
    {
        $routes = $this->routes;
        
        $dispatcher = \FastRoute\simpleDispatcher(function (\FastRoute\RouteCollector $r) use ($routes) {
            foreach ($routes as $route) {
                $r->addRoute(
                    $route['method'],
                    $route['url'],
                    $route['callable']
                );
            }
        });
        
        return $dispatcher;
    }
    
    private function filterUri($uri)
    {
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);
        
        return $uri;
    }
}
