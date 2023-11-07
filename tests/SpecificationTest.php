<?php

namespace DanBallance\OasLumen\Tests;

use PHPUnit\Framework\TestCase;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

use DanBallance\OasTools\Specification\Adapters\AdapterJCollect3;
use DanBallance\OasLumen\Specification;

class SpecificationTest extends TestCase
{
    public function testRoutesFromPetstore()
    {
        $path = dirname(__FILE__)  .
            '/fixtures/specifications/oas3/petstore-extended.yml';
        $spec = Specification::fromFile($path);
        $routes = $spec->getRoutes();
        $this->assertCount(4, $routes);
        
        // route 1
        $this->assertEquals(
            'get',
            $routes[0]->getMethod()
        );
        $this->assertEquals(
            '/pets',
            $routes[0]->getUri()
        );
        $this->assertEquals(
            'Pet',
            $routes[0]->getResource()
        );
        $this->assertEquals(
            'pet.list',
            $routes[0]->getName()
        );
        $this->assertEquals(
            '\\app\\Http\\Controllers',
            $routes[0]->getNamespace()
        );
        $this->assertEquals(
            'list',
            $routes[0]->getAction()
        );
        // route 2
        $this->assertEquals(
            'post',
            $routes[1]->getMethod()
        );
        $this->assertEquals(
            '/pets',
            $routes[1]->getUri()
        );
        $this->assertEquals(
            'Pet',
            $routes[1]->getResource()
        );
        $this->assertEquals(
            'pet.create',
            $routes[1]->getName()
        );
        $this->assertEquals(
            '\\app\\Http\\Controllers',
            $routes[1]->getNamespace()
        );
        $this->assertEquals(
            'create',
            $routes[1]->getAction()
        );
        // route 3
        $this->assertEquals(
            'get',
            $routes[2]->getMethod()
        );
        $this->assertEquals(
            '/pets/{id}',
            $routes[2]->getUri()
        );
        $this->assertEquals(
            'Pet',
            $routes[2]->getResource()
        );
        $this->assertEquals(
            'pet.read',
            $routes[2]->getName()
        );
        $this->assertEquals(
            '\\app\\Http\\Controllers',
            $routes[2]->getNamespace()
        );
        $this->assertEquals(
            'read',
            $routes[2]->getAction()
        );
        // route 4
        $this->assertEquals(
            'delete',
            $routes[3]->getMethod()
        );
        $this->assertEquals(
            '/pets/{id}',
            $routes[3]->getUri()
        );
        $this->assertEquals(
            'Pet',
            $routes[3]->getResource()
        );
        $this->assertEquals(
            'pet.delete',
            $routes[3]->getName()
        );
        $this->assertEquals(
            '\\app\\Http\\Controllers',
            $routes[3]->getNamespace()
        );
        $this->assertEquals(
            'delete',
            $routes[3]->getAction()
        );
    }

    public function testGetControllerAndActionFromMethod()
    {
        $path = dirname(__FILE__)  . '/fixtures/specifications/oas3/petstore-extended.yml';
        $spec = Specification::fromFile($path);
        $this->assertEquals(
            ['Pet', 'read'],
            $spec->getControllerAction(
                '\\app\\Http\\Controllers\\PetController::read'
            )
        );
        $this->assertEquals(
            ['NewPlayer', 'create'],
            $spec->getControllerAction(
                '\\DanBallance\\OasLumen\\Controllers\\NewPlayerController::create'
            )
        );
    }

    public function testGetOperationByControllerAction()
    {
        $path = dirname(__FILE__)  .
            '/fixtures/specifications/oas3/petstore-extended.yml';
        $spec = Specification::fromFile($path);
        $this->assertEquals(
            $spec->getOperation('findPets'),
            $spec->getOperationByControllerAction('crudl', 'list')
        );
    }

    public function testGetSchemaNameByControllerAction()
    {
        $path = dirname(__FILE__)  .
            '/fixtures/specifications/oas3/petstore-extended.yml';
        $spec = Specification::fromFile($path);
        $this->assertEquals(
            'Pet',  // no request or response schema - falls back to x-resource
            $spec->getSchemaNameByControllerAction(
                'crudl',
                'delete',
                $response = true
            )
        );
        $this->assertEquals(
            'Pet',  // read from Response schema
            $spec->getSchemaNameByControllerAction(
                'crudl',
                'create',
                $response = true
            )
        );
        $this->assertEquals(
            'NewPet',  // read from Request schema
            $spec->getSchemaNameByControllerAction(
                'crudl',
                'create',
                $response = false
            )
        );
    }

    public function testGetActionOperation()
    {
        $path = dirname(__FILE__)  .
        '/fixtures/specifications/oas3/player.yml';
        $spec = Specification::fromFile($path);
        $this->assertEquals(
            $spec->getOperation('crudl.list'),
            $spec->getActionOperation('list', 'Player')
        );
        $this->assertEquals(
            $spec->getOperation('crudl.create'),
            $spec->getActionOperation('create', 'Player')
        );
        $this->assertEquals(
            null,
            $spec->getActionOperation('list', 'Wrong')
        );
        $this->assertEquals(
            null,
            $spec->getActionOperation('wrong', 'Player')
        );
    }

    public function testPopulateRouteParams()
    {
        $path = dirname(__FILE__)  .
        '/fixtures/specifications/oas3/player.yml';
        $spec = Specification::fromFile($path);
        $this->assertEquals(
            [],
            $spec->lookupRouteParams(
                '/player',
                []
            )
        );
        $this->assertEquals(
            [],
            $spec->lookupRouteParams(
                '/player', 
                [2]
            )
        );
        $this->assertEquals(
            ['id' => 2],
            $spec->lookupRouteParams(
                '/player/id/{id}', 
                [2]
            )
        );
        $this->assertEquals(
            ['id' => 2],
            $spec->lookupRouteParams(
                '/player/{id}', 
                [2]
            )
        );
        $this->assertEquals(
            ['country' => 'uk', 'id' => 2],
            $spec->lookupRouteParams(
                '/player/country/{country}/id/{id}', 
                ['uk', 2]
            )
        );
        $this->assertEquals(
            ['id' => 'uk', 'country' => 2],
            $spec->lookupRouteParams(
                '/player/id/{id}/country/{country}', 
                ['uk', 2]
            )
        );
    }
}
