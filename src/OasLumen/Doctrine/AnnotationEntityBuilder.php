<?php

namespace DanBallance\OasLumen\Doctrine;

use DanBallance\OasLumen\Specification;
use DanBallance\OasLumen\Doctrine\AnnotationEntity;
use DanBallance\OasTools\Exceptions\SchemaNotFound;

class AnnotationEntityBuilder
{
    protected $spec;

    public function __construct(Specification $spec)
    {
        $this->spec = $spec;
    }

    public function make(string $resourceName) : AnnotationEntity
    {
        $schema = $this->spec->getSchema($resourceName, true);
        if (!$schema) {
            $err = "Could not find schema '{$resourceName}'.";
            throw new SchemaNotFound($err);
        }
        $annotationEntity = new AnnotationEntity();
        $annotationEntity->setClass($resourceName, $schema);
        foreach ($schema->toArray()['properties'] as $fieldName => $fieldSchema) {
            $annotationEntity->setField($fieldName, $fieldSchema);
        }
        return $annotationEntity;
    }
}
