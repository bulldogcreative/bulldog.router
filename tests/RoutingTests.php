<?php

use Bulldog\Router;
use PHPUnit\Framework\TestCase;
use Zend\Diactoros\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;

class RoutingTests extends TestCase
{
    public function testSimpleRunRoute()
    {
        $router = new Router;
        $router->get('/', 'callable');
        
        $request = new ServerRequest([], [], '/', 'GET');
        $routeInfo = $router->run($request);
        
        $this->assertInstanceOf(ServerRequestInterface::class, $request);
        $this->assertSame('callable', $routeInfo->handler());
        $this->assertInternalType('array', $routeInfo->vars());
    }
    
    public function testRouteWithVariables()
    {
        $router = new Router;
        $router->get('/user/{id}', 'callable');
        
        $request = new ServerRequest([], [], '/user/1', 'GET');
        $routeInfo = $router->run($request);
        
        $this->assertInstanceOf(ServerRequestInterface::class, $request);
        $this->assertSame('callable', $routeInfo->handler());
        $this->assertInternalType('array', $routeInfo->vars());
        $this->assertSame(['id' => '1'], $routeInfo->vars());
    }
    
    public function testFilterUriMethod()
    {
        $router = new Router;
        $router->get('/user/{id}', 'callable');
        
        $request = new ServerRequest([], [], '/user/1?wee=true', 'GET');
        $routeInfo = $router->run($request);
        
        $this->assertInstanceOf(ServerRequestInterface::class, $request);
        $this->assertSame('callable', $routeInfo->handler());
        $this->assertInternalType('array', $routeInfo->vars());
        $this->assertSame(['id' => '1'], $routeInfo->vars());
    }
}