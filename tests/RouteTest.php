<?php

namespace DanBallance\OasLumen\Tests;

use PHPUnit\Framework\TestCase;

use DanBallance\OasLumen\Routing\Route;
use DanBallance\OasLumen\Specification;

class RouteTest extends TestCase
{
    public function testRouteCreation()
    {
        $path = dirname(__FILE__)  .
            '/fixtures/specifications/oas3/petstore-extended.yml';
        $spec = Specification::fromFile($path);
        $operation = $spec->getOperation('findPets');
        $route = new Route($operation->toArray());
        $this->assertEquals('get', $route->getMethod());
        $this->assertEquals('/pets', $route->getUri());
        $this->assertEquals('pet.list', $route->getName());
        $this->assertEquals('\\app\\Http\\Controllers', $route->getNamespace());
        $this->assertEquals(
            '\\app\\Http\\Controllers\\CrudlController',
            $route->getController()
        );
        $this->assertEquals('list', $route->getAction());
    }

    public function testRouteCreationWithNamespace()
    {
        $path = dirname(__FILE__)  .
            '/fixtures/specifications/oas3/petstore-extended.yml';
        $spec = Specification::fromFile($path);
        $operation = $spec->getOperation('findPets');
        $route = new Route(
            $operation->toArray(),
            '\\DanBallance\\OasLumen\\Controllers'
        );
        $this->assertEquals(
            '\\DanBallance\\OasLumen\\Controllers',
            $route->getNamespace()
        );
    }
}
