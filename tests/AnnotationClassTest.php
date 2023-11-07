<?php

namespace DanBallance\OasLumen\Tests;

use PHPUnit\Framework\TestCase;
use DanBallance\OasLumen\Doctrine\AnnotationClass;
use DanBallance\OasLumen\Specification;

class AnnotationClassTest extends TestCase
{
    public function testExceptionThrownWhenSchemaNotFound()
    {
        $path = dirname(__FILE__)  . '/fixtures/specifications/oas3/player.yml';
        $spec = Specification::fromFile($path);
        $schemaName = 'Player';
        $schema = $spec->getSchema($schemaName, true);
        $annotationClass = new AnnotationClass($schemaName, $schema);
        $this->assertEquals(
            [
                '@ORM\Entity',
                '@ORM\Table(name="player")'
            ],
            $annotationClass->getAnnotations()
        );
    }
}
