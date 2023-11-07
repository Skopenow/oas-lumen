<?php

namespace DanBallance\OasLumen\Tests;

use PHPUnit\Framework\TestCase;
use DanBallance\OasLumen\Middleware\Builder;

class MiddlewareBuilderTest extends TestCase
{
    public function testMiddlewareStringBuiltCorrectly()
    {
        $builder = new Builder([
            'one' => \DanBallance\OasLumen\Middleware\One::class,
            'two' => \DanBallance\OasLumen\Middleware\Two::class,
        ]);
        $this->assertEquals(
            'one:123,test|two:123,test',
            $builder->make([
                'param1' => 123,
                'param2' => 'test'
            ])
        );
    }
}
