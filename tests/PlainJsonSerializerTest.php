<?php

namespace DanBallance\OasLumen\Tests;

use ReflectionClass;

use PHPUnit\Framework\TestCase;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

use DanBallance\OasLumen\Serializers\PlainJsonSerializer;
use DanBallance\OasLumen\Specification;
use DanBallance\OasLumen\Fractal\DynamicTransformer;
use DanBallance\OasLumen\Tests\fixtures\generatedPHP\Player;

class PlainJsonSerializerTest extends TestCase
{
    public function testItem()
    {
        $path = dirname(__FILE__)  . '/fixtures/specifications/oas3/player.yml';
        $spec = Specification::fromFile($path);
        $schemaName = 'Player';
        $dynamicTransformer = new DynamicTransformer($spec, $schemaName);
        $player = new Player('John Smith', 42, true);
        $reflectionClass = new ReflectionClass(
            'DanBallance\\OasLumen\\Tests\\fixtures\\generatedPHP\\Player'
        );
        $reflectionProperty = $reflectionClass->getProperty('id');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($player, 1);
        $resource = new Item($player, $dynamicTransformer, $schemaName);
        $manager = new Manager;
        $manager->setSerializer(new PlainJsonSerializer('http://localhost:8000'));
        $this->assertEquals(
            [
                'id' => 1,
                'name' => 'John Smith',
                'age' => 42,
                'isOnline' => true
            ],
            $manager->createData($resource)->toArray()
        );
    }
}
