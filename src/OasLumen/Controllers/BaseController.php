<?php

namespace DanBallance\OasLumen\Controllers;

use ReflectionClass;
use DanBallance\OasLumen\Specification;
use DanBallance\OasLumen\Http\Psr7Factory;
use DanBallance\OasLumen\Interfaces\StorageInterface;
use DanBallance\OasLumen\Interfaces\SerializationInterface;
use DanBallance\OasLumen\Interfaces\ValidationInterface;

class BaseController extends \Laravel\Lumen\Routing\Controller
{
    protected $spec;
    protected $validate;
    protected $persist;
    protected $serialize;
    protected $psr7Factory;

    public function __construct(
        Specification $spec,
        ValidationInterface $validate,
        StorageInterface $storage,
        SerializationInterface $serialize,
        Psr7Factory $psr7Factory
    ) {
        $this->spec = $spec;
        $this->validate = $validate;
        $this->storage = $storage;
        $this->serialize = $serialize;
        $this->psr7Factory = $psr7Factory;
    }

    /**
     * Returns lower case controller name with no namespace and 'Controller' removed,
     * I.e. for app\my\namspace\ExampleController it returns 'example'
     */
    protected function getControllerName()
    {
        $shortName = (new ReflectionClass($this))->getShortName();
        return strtolower(str_replace('Controller', '', $shortName));
    }

    protected function lookup(string $action) : array
    {
        $controllerName = $this->getControllerName();
        $reqSchemaName = $this->spec->getSchemaNameByControllerAction(
            $controllerName,
            $action,
            $request = false
        );
        $respSchemaName = $this->spec->getSchemaNameByControllerAction(
            $controllerName,
            $action,
            $request = true
        );
        $operation = $this->spec->getOperationByControllerAction(
            $controllerName,
            $action
        );
        return [$reqSchemaName, $respSchemaName, $operation];
    }
}
