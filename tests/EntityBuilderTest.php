<?php

namespace DanBallance\OasLumen\Tests;

use Psr\Http\Message\ServerRequestInterface;
use DanBallance\OasLumen\Specification;
use DanBallance\OasLumen\Doctrine\EntityBuilder;

class EntityBuilderTest extends TestCase
{
    public function testMake()
    {
        $path = dirname(__FILE__)  . '/fixtures/specifications/oas3/player.yml';
        $spec = Specification::fromFile($path);
        $schemaName = 'Player';
        $schema = $spec->getSchema($schemaName, true);
        $entityBuilder = new EntityBuilder($schemaName, $schema);
        $namespace = 'DanBallance\\OasLumen\\Tests\\fixtures\\generatedPHP\\';
        $entityBuilder->setNamespace($namespace);
        $name = 'John Smith';
        $age = 42;
        $isOnline = true;
        $entity = $entityBuilder->make([
            'name' => $name,
            'age' => $age,
            'isOnline' => $isOnline
        ]);
        $this->assertInstanceOf(
            "{$namespace}Player", 
            $entity
        );
        $this->assertEquals($name, $entity->getName());
        $this->assertEquals($age, $entity->getAge());
        $this->assertEquals($isOnline, $entity->getIsOnline());
    }
}
