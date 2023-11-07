<?php

namespace DanBallance\OasLumen\Doctrine;

use DanBallance\OasTools\specification\Fragments\FragmentInterface;

class AnnotationClass extends AnnotationCollection
{
    protected $name;
    protected $schema;

    public function __construct(string $name, FragmentInterface $schema)
    {
        $this->name = $name;
        $this->schema = $schema;
    }

    public function getSchema() : array
    {
        return $this->schema->toArray();
    }

    protected function makeAnnotations() : array
    {
        return [
            new Annotation(
                'Entity'
            ),
            new Annotation(
                'Table',
                ['name' => $this->getTableName()]
            )
        ];
    }

    protected function getTableName()
    {
        $array = $this->schema->toArray();
        if (isset($array['x-storage-name'])) {
            return $array['x-storage-name'];
        }
        return strtolower($this->name);
    }
}
