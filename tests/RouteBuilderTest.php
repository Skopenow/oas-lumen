<?php

namespace DanBallance\OasLumen\Tests;

use PHPUnit\Framework\TestCase;

use DanBallance\OasLumen\Routing\RouteBuilder;
use DanBallance\OasLumen\Specification;

class RouteBuilderTest extends TestCase
{
    public function testRoutesFromPetstore()
    {
        $app = new \Laravel\Lumen\Application();
        $router = new \Laravel\Lumen\Routing\Router($app);
        $path = dirname(__FILE__)  . '/fixtures/specifications/oas3/petstore-extended.yml';
        $spec = Specification::fromFile($path);
        $builder = new RouteBuilder($spec);
        $router = $builder->populate($router);

        $routes = $router->getRoutes();
        $this->assertCount(4, $routes);
        $this->assertEquals(
            ['GET/pets', 'POST/pets', 'GET/pets/{id}', 'DELETE/pets/{id}'],
            array_keys($routes)
        );
        $route1 = $routes['GET/pets'];
        $route2 = $routes['POST/pets'];
        $route3 = $routes['GET/pets/{id}'];
        $route4 = $routes['DELETE/pets/{id}'];
        // route 1
        $this->assertEquals(
            'GET',
            $route1['method']
        );
        $this->assertEquals(
            '/pets',
            $route1['uri']
        );
        $this->assertEquals(
            'pet.list',
            $route1['action']['as']
        );
        $this->assertEquals(
            '\\app\\Http\\Controllers\\CrudlController@list',
            $route1['action']['uses']
        );
        // route 2
        $this->assertEquals(
            'POST',
            $route2['method']
        );
        $this->assertEquals(
            '/pets',
            $route2['uri']
        );
        $this->assertEquals(
            'pet.create',
            $route2['action']['as']
        );
        $this->assertEquals(
            '\\app\\Http\\Controllers\\CrudlController@create',
            $route2['action']['uses']
        );
        // route 3
        $this->assertEquals(
            'GET',
            $route3['method']
        );
        $this->assertEquals(
            '/pets/{id}',
            $route3['uri']
        );
        $this->assertEquals(
            'pet.read',
            $route3['action']['as']
        );
        // route 4
        $this->assertEquals(
            'DELETE',
            $route4['method']
        );
        $this->assertEquals(
            '/pets/{id}',
            $route4['uri']
        );
        $this->assertEquals(
            'pet.delete',
            $route4['action']['as']
        );
        $this->assertEquals(
            '\\app\\Http\\Controllers\\CrudlController@delete',
            $route4['action']['uses']
        );
    }
}
