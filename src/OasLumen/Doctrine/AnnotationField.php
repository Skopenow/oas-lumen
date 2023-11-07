<?php

namespace DanBallance\OasLumen\Doctrine;

class AnnotationField extends AnnotationCollection
{
    protected $name;
    protected $schema;

    public function __construct(string $name, array $schema)
    {
        $this->name = $name;
        $this->schema = $schema;
    }

    public function getSchema() : array
    {
        return $this->schema;
    }

    public function isPrimaryKey()
    {
        if (isset($this->schema['x-primary-key'])) {
            return true;
        }
        return false;
    }

    protected function makeAnnotations() : array
    {
        $annotations = [];
        if ($this->isPrimaryKey()) {
            $annotations[] = new Annotation('Id');
            $annotations[] = new Annotation('GeneratedValue');
        }
        $annotations[] = $this->getColumnAnnotation();
        return $annotations;
    }

    protected function getColumnAnnotation()
    {
        return new Annotation('Column', ['type' => $this->getType()]);
    }

    protected function getType()
    {
        $type = $this->schema['type'];
        switch ($type) {
            case 'integer':
                return 'integer';
            case 'string':
                return 'string';
            case 'boolean':
                return 'boolean';
            default:
                $err = "Invalid schema type passed '{$type}'.";
                throw new Runtimexcetion($err);
        }
    }
}
