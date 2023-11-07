<?php

namespace DanBallance\OasLumen\Tests;

use PHPUnit\Framework\TestCase;
use DanBallance\OasLumen\Specification;
use DanBallance\OasLumen\Fractal\DynamicTransformer;
use DanBallance\OasLumen\Tests\fixtures\generatedPHP\Player;
use ReflectionClass;

class DynamicTransformerTest extends TestCase
{
    public function testSimpleEntity()
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
        $this->assertEquals(
            [
                'id' => 1,
                'name' => 'John Smith',
                'age' => 42,
                'isOnline' => true,
                'links' => [
                    [
                        'rel' => 'self',
                        'uri' => "/player/id/1",
                    ]
                ]
            ],
            $dynamicTransformer->transform($player)
        );
    }
}
