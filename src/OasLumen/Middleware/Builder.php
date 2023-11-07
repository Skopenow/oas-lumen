<?php

namespace DanBallance\OasLumen\Middleware;

class Builder
{
    protected $routeMiddleware = [];

    public function __construct(array $routeMiddleware)
    {
        $this->routeMiddleware = $routeMiddleware;
    }

    /**
     * Any parameters passed in here get passed to every route middleware
     */
    public function make(array $middlewareParams)
    {
        $middlewareConfigs = [];
        foreach ($this->routeMiddleware as $name => $class) {
            $middlewareConfigs[] = "{$name}:" . implode(',', $middlewareParams);
        }
        return implode('|', $middlewareConfigs);
    }
}
