<?php

namespace DanBallance\OasLumen\Doctrine;

use Psr\Http\Message\ServerRequestInterface;
use DanBallance\OasTools\Specification\Fragments\FragmentInterface;

class EntityBuilder
{
    protected $namespace = 'App\\Entities\\';
    protected $name;
    protected $schema;

    public function __construct(string $name, FragmentInterface $schema)
    {
        $this->name = $name;
        $this->schema = $schema;
    }

    public function make(array $data)
    {
        $className = "{$this->namespace}{$this->name}";
        $entity = new $className();
        foreach ($data as $fieldName => $fieldValue) {
            $setter = 'set' . ucfirst($fieldName);
            if (method_exists($entity, $setter)) {
                $entity->$setter($fieldValue);
            }
        }
        return $entity;
    }

    public function update($entity, array $requestBody)
    {
        foreach ($requestBody as $fieldName => $fieldValue) {
            $setter = 'set' . ucfirst($fieldName);
            if (method_exists($entity, $setter)) {
                $entity->$setter($fieldValue);
            }
        }
        return $entity;
    }

    public function setNamespace(string $namespace)
    {
        $this->namespace = $namespace;
    }
}
