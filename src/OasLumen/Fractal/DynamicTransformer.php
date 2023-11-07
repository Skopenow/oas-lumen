<?php

namespace DanBallance\OasLumen\Fractal;

use League\Fractal\TransformerAbstract;
use DanBallance\OasLumen\Specification;
use DanBallance\OasTools\Specification\Fragments\FragmentInterface;

class DynamicTransformer extends TransformerAbstract
{
    protected $spec;
    protected $schemaName;

    public function __construct(Specification $specification, string $schemaName)
    {
        $this->spec = $specification;
        $this->schemaName = $schemaName;
    }

    public function transform($entity)
	{
        $schema = $this->spec->getSchema($this->schemaName, true);
        $data = [];
        $schemaArr = $schema->toArray();
        foreach ($schemaArr['properties'] as $name => $schema) {
            $getter = 'get' . ucfirst($name);
            $data[$name] = $this->toType($entity->$getter(), $schema);
        }
        $readOperation = $this->spec->getActionOperation('read', $this->schemaName);
        if ($readOperation) {
            $data['links'] = [
               [
                   'rel' => 'self',
                    'uri' => $this->makeSelfLink($readOperation, $entity)
               ]
            ];
        }
        return $data;
    }

    protected function makeSelfLink(FragmentInterface $readOperation, $entity)
    {
        $path = strtolower($readOperation->toArray()['path']);
        preg_match_all('/{(.*?)}/', $path, $matches);
        if ($matches && isset($matches[1])) {
            $params = $matches[1];
            foreach ($params as $param) {
                $getter = "get" . ucfirst($param);
                if (method_exists($entity, $getter)) {
                    $val = $entity->$getter();
                    $path = str_replace('{' . $param . '}', $val, $path);
                }
            }
        }
        return $path;
    }
    
    protected function toType($value, $schema)
    {
        $type = $schema['type'];
        switch ($type) {
            case 'integer':
                return intval($value);
            case 'string':
                return strval($value);
            case 'boolean':
                return boolval($value);
            default:
                return $value;
        }
    }
}
