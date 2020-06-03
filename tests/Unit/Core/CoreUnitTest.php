<?php

use PHPUnit\Framework\TestCase;
use Core\Core;
use Slim\Http\Environment;
use Slim\Http\Request;

class CoreUnitTest extends TestCase {

    /**
     * @var Core
     */
    protected $core;

    public function setUp() : void {
        $appDirectory = dirname(__FILE__);
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/test'
        ]);
        $this->core = new Core($appDirectory);
        $this->core->getApp()->getContainer()['request'] = Request::createFromEnvironment($env);
    }

    public function testServices() {
        $this->core->handle(true);
        $container = $this->core->getApp()->getContainer();
        $this->assertTrue($container->has('test.service'));
    }

    public function testRoutes() {
        $this->core->handle(true);
        /** @var \Slim\Interfaces\RouterInterface */
        $router = $this->core->getApp()->getContainer()->get('router');
        $route = $router->lookupRoute('test');
        $response = $this->core->getApp()->getContainer()->get('response');
        
        $this->assertStringContainsString("test run successful", $response->getBody()->getContents());
    }

}