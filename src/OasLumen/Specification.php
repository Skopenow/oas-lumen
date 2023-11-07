<?php

namespace DanBallance\OasLumen;

use DanBallance\OasTools\Specification\Specification3Interface;
use DanBallance\OasTools\Specification\Adapters\AdapterJCollect3;
use DanBallance\OasTools\Specification\Fragments\FragmentInterface;
use DanBallance\OasLumen\Routing\Route;

/**
 * A wrapper around the OasTools class that creates objects that are
 * easy to consume for the Lumen framework
 */
class Specification
{ use \App\SpecificationOverride; use \App\SpecificationOverride; use \App\SpecificationOverride;
    protected $spec;
    protected $namespace;
    protected $routes = [];

    public function __construct(
        Specification3Interface $spec,
        string $namespace = null
    ) {
        $this->spec = $spec;
        $this->namespace = $namespace;
    }

    public function __call($method, $args)
    {
        if (method_exists($this->spec, $method)) {
            return call_user_func_array([$this->spec, $method], $args);
        }
    }

    public static function _fromFile(string $path) : Specification
    {
        $spec3implementation = new AdapterJCollect3($path);
        return new static($spec3implementation);
    }

    public function setNamespace(string $namespace) : void
    {
        $this->namespace = $namespace;
    }

    public function _getRoutes() : array
    {
        if (!$this->routes) {
            $fragment = $this->spec->getOperations();
            foreach ($fragment->toArray() as $operation) {
                $this->routes[] = new Route(
                    $operation,
                    $this->namespace
                );
            }
        }
        return $this->routes;
    }

    public function getOperationByControllerAction(
        string $controller,
        string $action
    ) : ?FragmentInterface {
        return $this->findOperation([
            'x-controller' => $controller,
            'x-action' => $action
        ]);
    }

    public function getSchemaByControllerAction(
        string $controller,
        string $action,
        bool $response = true
    ) : ?FragmentInterface {
        $schemaName = $ths->getSchemaNameByControllerAction(
            $controller,
            $action,
            $response
        );
        return $this->spec->getSchema($schemaName, true);
    }

    public function getSchemaNameByControllerAction(
        string $controller,
        string $action,
        bool $response = true
    ) : ?string {
        $operation = $this->getOperationByControllerAction($controller, $action);
        if (!$operation) {
            return null;
        }
        if (!$response && $operation->hasRequestSchema()) {
            return $operation->getRequestSchemaName();
        }
        if ($response && $operation->hasResponseSchema()) {
            return $operation->getResponseSchemaName();
        }
        if (isset($operation->toArray()['x-resource'])) {
            return $operation->toArray()['x-resource'];
        }
        return null;
    }

    /**
     * $method is of format: /Name/Spaced/ResourceNameController::method
     */
    public function getControllerAction(string $method) : array
    {
        [$namespace, $action] = explode('::', $method);
        $parts = explode('\\', $namespace);
        $controller = end($parts);
        $controller = str_replace('Controller', '', $controller);
        return [$controller, $action];
    }

    public function getActionOperation(
        string $action,
        string $schemaName
    ) : ?FragmentInterface {
        return $this->findOperation([
            'x-resource' => $schemaName,
            'x-action' => $action
        ]);
    }

    public function lookupRouteParams(
        string $path,
        array $routeParams
    ) : array {
        $result = [];
        preg_match_all('/{(.*?)}/', $path, $matches);
        if ($matches && isset($matches[1])) {
            $params = $matches[1];
            for ($i = 0; $i < count($params); $i++) {
                if (isset($routeParams[$i])) {
                    $result[$params[$i]] = $routeParams[$i];
                }
            }
        }
        return $result;
    }

    /**
     * Pass an array of k => v pairs, where the key is the field
     * in the operation array to lookup and the value is what we test against.
     * 
     * This method is probably a good candidate to move into 
     * DanBallance\OasTools\Specification\Specification3Interface
     * 
     */
    protected function findOperation(array $criteria) : ?FragmentInterface
    {
        foreach ($this->spec->getOperationIds()->toArray() as $id) {
            $operation = $this->spec->getOperation($id);
            $opArray = $operation->toArray();
            $found = true;
            foreach ($criteria as $field => $value) {
                if (strtolower($opArray[$field]) !== strtolower($value)) {
                    $found = false;
                    continue;
                }
            }
            if ($found == true) {
                return $operation;
            }
        }
        return null;
    }
}
