<?php

namespace DanBallance\OasLumen\Doctrine;

abstract class AnnotationCollection
{
    abstract protected function makeAnnotations() : array;
    abstract public function getSchema() : array;

    public function getAnnotations(bool $asString = true) : array
    {
        $annotations = $this->makeAnnotations();
        if ($asString) {
            return array_map(
                function (Annotation $annotation) {
                    return (string) $annotation;
                },
                $annotations
            );
        }
        return $annotatios;

    }
}
