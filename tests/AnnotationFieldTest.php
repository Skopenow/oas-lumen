<?php

namespace DanBallance\OasLumen\Tests;

use PHPUnit\Framework\TestCase;
use DanBallance\OasLumen\Doctrine\AnnotationField;
use DanBallance\OasLumen\Specification;

class AnnotationFieldTest extends TestCase
{
    public function testExceptionThrownWhenSchemaNotFound()
    {
        $path = dirname(__FILE__)  . '/fixtures/specifications/oas3/player.yml';
        $spec = Specification::fromFile($path);
        $schemaName = 'Player';
        $schema = $spec->getSchema($schemaName, true);
        $properties = $schema->toArray()['properties'];
        // id
        $fieldName = 'id';
        $fieldSchema = $properties[$fieldName];
        $annotationField = new AnnotationField($fieldName, $fieldSchema);
        $this->assertEquals(
            [
                '@ORM\Id',
                '@ORM\GeneratedValue',
                '@ORM\Column(type="integer")'
            ],
            $annotationField->getAnnotations()
        );
        // name
        $fieldName = 'name';
        $fieldSchema = $properties[$fieldName];
        $annotationField = new AnnotationField($fieldName, $fieldSchema);
        $this->assertEquals(
            [
                '@ORM\Column(type="string")'
            ],
            $annotationField->getAnnotations()
        );
        // age
        $fieldName = 'age';
        $fieldSchema = $properties[$fieldName];
        $annotationField = new AnnotationField($fieldName, $fieldSchema);
        $this->assertEquals(
            [
                '@ORM\Column(type="integer")'
            ],
            $annotationField->getAnnotations()
        );
        // isOnline
        $fieldName = 'isOnline';
        $fieldSchema = $properties[$fieldName];
        $annotationField = new AnnotationField($fieldName, $fieldSchema);
        $this->assertEquals(
            [
                '@ORM\Column(type="boolean")'
            ],
            $annotationField->getAnnotations()
        );
    }
}
