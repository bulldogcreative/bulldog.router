<?php

use Bulldog\Router;
use PHPUnit\Framework\TestCase;
use Zend\Diactoros\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;

class RouterTests extends TestCase
{
    public function testAddRouteMethod()
    {
        $router = new Router;
        $router->addRoute('get', '/', 'callable');
        $routes = $router->routes();
        $this->assertEquals($routes[0]['method'], 'GET');
    }
    
    public function testAddRouteMethodSeveralTimes()
    {
        $routes = [
            ['get', '/', 'callable'],
            ['post', '/', 'class@method'],
            ['put', '/', 'AnotherClass@put'],
            ['delete', '/', 'OtherClass@delete'],
        ];
        
        $router = new Router;
        
        foreach($routes as $route) {
            $router->addRoute($route[0], $route[1], $route[2]);
        }
        
        $allRoutes = $router->routes();
        $i = 0;
        
        foreach($routes as $route) {
            $this->assertSame(strtoupper($route[0]), $allRoutes[$i]['method']);
            $i++;
        }
    }
    
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Not a valid HTTP method.
     */
    public function testInvalidMethodException()
    {
        $router = new Router;
        $router->addRoute('not', '/', 'call');
    }
    
    public function testGetMethod()
    {
        $router = new Router;
        $router->get('/', 'callable');
        $routes = $router->routes();
        $this->assertEquals($routes[0]['url'], '/');
        $this->assertEquals($routes[0]['method'], 'GET');
        $this->assertEquals($routes[0]['callable'], 'callable');
    }
    
    public function testPostMethod()
    {
        $router = new Router;
        $router->post('/', 'callable');
        $routes = $router->routes();
        $this->assertEquals($routes[0]['url'], '/');
        $this->assertEquals($routes[0]['method'], 'POST');
        $this->assertEquals($routes[0]['callable'], 'callable');
    }
    
    public function testPutMethod()
    {
        $router = new Router;
        $router->put('/', 'callable');
        $routes = $router->routes();
        $this->assertEquals($routes[0]['url'], '/');
        $this->assertEquals($routes[0]['method'], 'PUT');
        $this->assertEquals($routes[0]['callable'], 'callable');
    }
    
    public function testDeleteMethod()
    {
        $router = new Router;
        $router->delete('/', 'callable');
        $routes = $router->routes();
        $this->assertEquals($routes[0]['url'], '/');
        $this->assertEquals($routes[0]['method'], 'DELETE');
        $this->assertEquals($routes[0]['callable'], 'callable');
    }
    
    public function testRouteInfoMethod()
    {
        $router = new Router;
        $router->delete('/', 'callable');
        $routeInfo = $router->routeInfo();
        $this->assertInternalType('array', $routeInfo);
    }
}