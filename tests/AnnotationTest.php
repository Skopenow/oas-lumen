<?php

namespace DanBallance\OasLumen\Tests;

use PHPUnit\Framework\TestCase;
use DanBallance\OasLumen\Doctrine\Annotation;

class AnnotationTest extends TestCase
{
    public function testToStringNoParams()
    {
        $annotation = new Annotation('Entity');
        $this->assertEquals(
            '@ORM\Entity',
            (string) $annotation
        );
    }

    public function testToStringWithSimpleString()
    {
        $annotation = new Annotation('Column', ['type' => 'string']);
        $this->assertEquals(
            '@ORM\Column(type="string")',
            (string) $annotation
        );
    }

    public function testToStringWithSimpleInteger()
    {
        $annotation = new Annotation('Column', ['length' => 2]);
        $this->assertEquals(
            '@ORM\Column(length=2)',
            (string) $annotation
        );
    }

    public function testToStringWithSimpleBoolean()
    {
        $annotation = new Annotation('Column', ['unique' => true]);
        $this->assertEquals(
            '@ORM\Column(unique=true)',
            (string) $annotation
        );
    }

    public function testToStringWithArray()
    {
        $annotation = new Annotation(
            'Column',
            [
                'options' => [
                    'fixed' => true,
                    'comment' => 'Initial letters of first and last name'
                ]
            ]
        );
        $this->assertEquals(
            '@ORM\Column(options={"fixed":true, "comment":"Initial letters of first and last name"})',
            (string) $annotation
        );
        
    }

    public function testToStringWithComplexParams()
    {
        $annotation = new Annotation(
            'Column',
            [
                'type' => 'string',
                'length' => 2,
                'options' => ['fixed' => true, 'comment' => 'Initial letters of first and last name']
            ]
        );
        $this->assertEquals(
            '@ORM\Column(type="string", length=2, options={"fixed":true, "comment":"Initial letters of first and last name"})',
            (string) $annotation
        );
        
    }
}
