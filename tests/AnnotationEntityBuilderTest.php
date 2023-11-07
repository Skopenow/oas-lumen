<?php

namespace DanBallance\OasLumen\Tests;

use PHPUnit\Framework\TestCase;
use DanBallance\OasTools\Exceptions\SchemaNotFound;
use DanBallance\OasLumen\Specification;
use DanBallance\OasLumen\Doctrine\AnnotationEntityBuilder;

class AnnotationEntityBuilderTest extends TestCase
{
    public function testExceptionThrownWhenSchemaNotFound()
    {
        $this->expectException(SchemaNotFound::class);
        $path = dirname(__FILE__)  . '/fixtures/specifications/oas3/player.yml';
        $spec = Specification::fromFile($path);

        $annotationEntityBuilder = new AnnotationEntityBuilder($spec);
        $annotationEntity = $annotationEntityBuilder->make('Unknown');
    }

    public function testInstanceOfResourceWithSchemaPropertiesSet()
    {
        $path = dirname(__FILE__)  . '/fixtures/specifications/oas3/player.yml';
        $spec = Specification::fromFile($path);

        $annotationEntityBuilder = new AnnotationEntityBuilder($spec);
        $annotationEntity = $annotationEntityBuilder->make('Player');
        $this->assertEquals(
            [
                '@ORM\Entity',
                '@ORM\Table(name="player")'
            ],
            $annotationEntity->getClassAnnotation()->getAnnotations()
        );
        $this->assertEquals(
            [
                '@ORM\Id',
                '@ORM\GeneratedValue',
                '@ORM\Column(type="integer")'
            ],
            $annotationEntity->getFieldAnnotation('id')->getAnnotations()
        );
        $this->assertEquals(
            [
                '@ORM\Column(type="string")'
            ],
            $annotationEntity->getFieldAnnotation('name')->getAnnotations()
        );
        $this->assertEquals(
            [
                '@ORM\Column(type="integer")'
            ],
            $annotationEntity->getFieldAnnotation('age')->getAnnotations()
        );
        $this->assertEquals(
            [
                '@ORM\Column(type="boolean")'
            ],
            $annotationEntity->getFieldAnnotation('isOnline')->getAnnotations()
        );
    }
}
