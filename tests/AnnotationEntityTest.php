<?php

namespace DanBallance\OasLumen\Tests;

use PHPUnit\Framework\TestCase;
use DanBallance\OasLumen\Doctrine\AnnotationEntity;
use DanBallance\OasLumen\Doctrine\AnnotationClass;
use DanBallance\OasLumen\Doctrine\AnnotationField;
use DanBallance\OasLumen\Specification;

class AnnotationEntityTest extends TestCase
{
    public function testSetGetClass()
    {
        $schema = $this->getSchema('Player');
        $annotationEntity = new AnnotationEntity();
        $annotationEntity->setClass('Player', $schema);
        $this->assertEquals(
            [
                '@ORM\Entity',
                '@ORM\Table(name="player")'
            ],
            $annotationEntity->getClassAnnotation()->getAnnotations()
        );
    }

    public function testSetGetField()
    {
        $schema = $this->getSchema('Player');
        $fieldSchema = $schema->toArray()['properties']['id'];
        $annotationEntity = new AnnotationEntity();
        $annotationEntity->setField('id', $fieldSchema);
        $this->assertEquals(
            [
                '@ORM\Id',
                '@ORM\GeneratedValue',
                '@ORM\Column(type="integer")'
            ],
            $annotationEntity->getFieldAnnotation('id')->getAnnotations()
        );
    }

    protected function getSchema($schemaName)
    {
        $path = dirname(__FILE__)  . '/fixtures/specifications/oas3/player.yml';
        $spec = Specification::fromFile($path);
        return $spec->getSchema($schemaName, true);
    }
}
