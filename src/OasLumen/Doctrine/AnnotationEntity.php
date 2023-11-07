<?php

namespace DanBallance\OasLumen\Doctrine;

use DanBallance\OasTools\specification\Fragments\FragmentInterface;

class AnnotationEntity
{
    protected $classAnnotation;
    protected $fieldAnnotations = [];

    public function getClassAnnotation() : AnnotationClass
    {
        return $this->classAnnotation;
    }
    
    public function getFieldAnnotations() : array
    {
        return $this->fieldAnnotations;
    }

    public function getFieldAnnotation(string $fieldName) : AnnotationField
    {
        if (isset($this->fieldAnnotations[$fieldName])) {
            return $this->fieldAnnotations[$fieldName];
        }
        $err = "Cannot find any annotations for field name '{$fieldName}'.";
        throw new RuntimeException($err);
    }

    public function setClass(string $schemaName, FragmentInterface $schema)
    {
        $this->classAnnotation = new AnnotationClass($schemaName, $schema);
    }

    public function setField(string $fieldName, array $schema)
    {
        $this->fieldAnnotations[$fieldName] = new AnnotationField($fieldName, $schema);
    }
}
