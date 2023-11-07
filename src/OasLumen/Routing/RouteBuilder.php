<?php

namespace DanBallance\OasLumen\Routing;

use DanBallance\OasLumen\Specification;
use DanBallance\OasLumen\Middleware\Builder;
use Laravel\Lumen\Routing\Router;

class RouteBuilder
{
    protected $spec;
    protected $middlewareBuilder;

    public function __construct(Specification $spec)
    {
        $this->spec = $spec;
    }

    public function setMiddlewareBuilder(Builder $builder) : void {
        $this->middlewareBuilder = $builder;
    }

    public function populate(Router $router) : Router
    {
        foreach ($this->spec->getRoutes() as $route) {
            $router = $this->addRoute($router, $route);
        }
        return $router;
    }

    protected function addRoute(Router $router, RouteInterface $route) : Router
    {
        $method = $route->getMethod();
        $uri = $route->getUri();
        $name = $route->getName();
        $controller = $route->getController();
        $action = $route->getAction();
        $routeConfig = [
            'as' => $name,
            'uses' => "{$controller}@{$action}"
        ];
        if ($this->middlewareBuilder) {
            $routeConfig['middleware'] = $this->middlewareBuilder->make([
                'operationId' => $route->getOperationId()
            ]);
        }
        $router->$method($uri, $routeConfig);
        return $router;
    }
}
