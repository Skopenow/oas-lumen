<?php

namespace DanBallance\OasLumen\Routing;

/**
 * This class translates the data from OAS schema dialect to Lumen route dialect
 */
class Route implements RouteInterface
{
    protected $operation;
    protected $namespace;

    public function __construct(
        array $operation,
        ?string $namespace = '\\app\\Http\\Controllers'
    ) {
        $this->operation = $operation;
        $this->namespace = $namespace ?? $namespace ?? '\\app\\Http\\Controllers';
    }

    public function getMethod() : string
    {
        return strtolower($this->operation['method']);
    }

    public function getUri() : string
    {
        return $this->operation['path'];
    }

    public function getResource() : string
    {
        if (isset($this->operation['x-resource'])) {
            return $this->operation['x-resource'];
        }
        return 'Default';
    }

    public function getName() : string
    {
        return strtolower($this->getResource()) . '.' . $this->getAction();
    }

    public function getNamespace() : string
    {
        return $this->namespace;
    }

    public function getController() : string
    {
        $controller = 'Default';
        if (isset($this->operation['x-controller'])) {
            $controller = ucfirst($this->operation['x-controller']);
        }
        return $this->getNamespace() . '\\' . "{$controller}Controller";
    }

    public function getAction() : string
    {
        if (isset($this->operation['x-action'])) {
            return $this->operation['x-action'];
        }
        return $this->getOperationId();
    }

    public function getOperationId() : ?string
    {
        if (isset($this->operation['operationId'])) {
            return (string) $this->operation['operationId'];
        }
        return null;
    }
}
