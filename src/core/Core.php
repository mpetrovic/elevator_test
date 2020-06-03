<?php

namespace Core;

use Slim\App;
use Slim\Container;
use Symfony\Component\Yaml\Yaml;
use Prophecy\Exception\Doubler\ClassNotFoundException;

/**
	* Core handler for apps.
	*
	* Class1 description.
	*
	* @version 1.0
	* @author Matt
	*/
class Core {

    /**
        * Reference to Slim application that handles routing and delivery.
        *
        * @var \Slim\App
        */
    private $app;

    /**
        * Root of the application.
        *
        * @var string
        */
    private $appRoot;

    /**
        * Determine the root of the application based on assumptions.
        * @return string
        */
    protected static function guessApplicationRoot() {
        return dirname(dirname(substr(__DIR__, 0, -strlen(__NAMESPACE__))));
    }

    /**
     * Constructor.

     * @param null|string $appRoot
     */
    public function __construct(string $appRoot = null) {
        if (is_null($appRoot)) {
            $this->appRoot = self::guessApplicationRoot();
        }
        else {
            $this->appRoot = $appRoot;
        }

    }

    /**
     * Handles the execution of the app.
     */
    public function handle($silent = false) {
        $app = $this->getApp();

        $this->discoverServices($app);

        $this->findRoutes($app);

        $app->run($silent);
    }

    /**
     * Instantiates the app if it doesn't exist and returns it.

     * @return App
     */
    public function getApp() {
        if (is_null($this->app)) {
            $this->app = new App(new Container());
        }

        return $this->app;
    }

    /**
     * Discover routes from *.router.yml files.

     * A router file should contain at least one route under the 'routes' heading.
     * A route requires a name, used as the key, an HTTP method, a uri, and a callback.
     * The callback class should implement the RequestHandlerInterface.
     * A router file should be structured as such:
     * routes:
     *   example-route:
     *     method: get
     *     uri: 'example/route/{argument}'
     *     callback: ExampleRoute:handleRequest
     * @param App $app
     */
    private function findRoutes(App $app) {
        $routing_table = [];
        $route_definitions = [];

        $route_files = glob($this->appRoot.'/*/*.router.yml');
        foreach ($route_files as $file_name) {
            $routes = Yaml::parseFile($file_name);
            $key = key($routes);    // for some reason, hard-coding the key wasn't working
            $route_definitions = array_merge($route_definitions, $routes[$key]);
        }

        // alter hook or event here

        foreach ($route_definitions as $name => $r) {
            $route = [];
            $route['name'] = $name;
            $route += $r;

            $routing_table[$name] = $route;
        }

        foreach ($routing_table as $r) {
            $method = $r['method'];
            $app->{$method}($r['uri'], $r['callback']);
        }
    }

    /**
     * Discover services from *.services.yml files.
     *
     * A service file should contain one or more service definitions located under the 'services' heading.
     * A service definition contains a class and class_path field, and an optional arguments array with the name of the service being to be passed into the constructor
     * Eg.
     * services:
     *   dependencyService:
     *     class: DependencyService
     *     class_path: path/to/DependencyService.php
     *   exampleService:
     *     class: ExampleService
     *     class_path: path/to/ExampleService.php
     *     arguments:
     *       - dependencyService

     * @param App $app
     * @throws ClassNotFoundException
     * @return void
     */
    private function discoverServices(App $app) {
        $definitions = [];
        $service_files = glob($this->appRoot.'/*/*.services.yml');
        foreach ($service_files as $file_name) {
            $services = Yaml::parseFile($file_name);
            $key = key($services);
            $definitions = array_merge($definitions, $services[$key]);
        }

        $container = $this->getApp()->getContainer();
        foreach ($definitions as $name => $s) {
            $container[$name] = function ($container) use ($s) {
                if (!class_exists($s['class'])) {
                    include_once($this->appRoot.'/'.$s['class_path']);
                }
                if (class_exists($s['class'])) {
                    if (isset($s['arguments'])) {
                        $arguments = [];
                        foreach ($s['arguments'] as $i => $service) {
                            if ($container->has($service)) {
                                $arguments[$i] = $container->get($service);
                            }
                        }
                        return new $s['class'](...$arguments);
                    }
                    return new $s['class']();
                }
                throw new ClassNotFoundException("Class ".$s['class']." not found.", $s['class']);
            };
        }
    }

}