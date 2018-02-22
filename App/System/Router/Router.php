<?php
namespace App\System\Router;

class Router {

    private $url;
    private $routes = [];
    private $namedRoutes = [];
    private $fallback;

    public function __construct($url) {
        if(sizeof($url) === 0) {
            $url['url'] = '/';
        }

        $this->url = $url['url'];
    }

    public function get($path, $callable, $name = null) {
        return $this->add($path, $callable, $name, 'GET');
    }

    public function post($path, $callable, $name = null) {
        return $this->add($path, $callable, $name, 'POST');
    }

    public function error($callable) {
        $this->fallback = $callable;
    }

    private function add($path, $callable, $name, $method) {
        $route = new Route($path, $callable);
        $this->routes[$method][] = $route;

        if(is_string($callable) && $name === null) {
            $name = $callable;
        }

        if($name) {
            $this->namedRoutes[$name] = $route;
        }

        return $route;
    }

    public function run() {
        if(!isset($this->routes[$_SERVER['REQUEST_METHOD']])) {
            throw new RouterException('REQUEST_METHOD does not exist');
        }

        foreach($this->routes[$_SERVER['REQUEST_METHOD']] as $route) {
            if($route->match($this->url)) {
                return $route->call();
            }
        }

        return call_user_func_array($this->fallback, []);
    }

    public function url($name, $params = []) {
        if(!isset($this->namedRoutes[$name])) {
            throw new RouterException('No route matches this name');
        }

        return $this->namedRoutes[$name]->getUrl($params);
    }

}
