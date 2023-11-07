<?php

namespace DanBallance\OasLumen\Validation;

use Psr\Http\Message\ServerRequestInterface;
use DanBallance\OasTools\Specification\Fragments\FragmentInterface;
use DanBallance\OasLumen\Specification;

class SchemaValidator
{
    private $spec;

    public function __construct(Specification $spec)
    {
        $this->spec = $spec;
    }

    public function setup(
        ServerRequestInterface $request,
        array $routeParams,
        FragmentInterface $operation,
        string $schemaName
    ) : array {
        $requestBody = json_decode($request->getBody()->__toString(), true);
        $schema = $this->spec->getSchema($schemaName, true);
        $data = [];
        $rules = [];
        foreach ($schema->getProperties() as $name => $definition) {
            if (isset($requestBody[$name])) {
                $data[$name] = $requestBody[$name];
                $rules[$name] = $this->makeRules(
                    $name,
                    $definition,
                    $schema->isRequired($name)
                );
            }
        }
        return [$data, $rules];
    }

    protected function makeRules(
        string $name,
        array $definition,
        bool $isRequired = false
    ) : string {
        $rules = [];
        // type
        if (isset($definition['type'])) {
            $rules = array_merge(
                $rules,
                $this->makeType($definition)
            );
        }
        // required
        if ($isRequired) {
            $rules[] = 'required';
        }
        return implode('|', $rules);
    }

    /**
     * Return one or more Laravel rules to add to the validation definition.
     */
    protected function makeType(array $definition) : array
    {
        $rules = [];
        switch ($definition['type']) {
            case 'string':
                $rules[] = 'string';
                break;
            case 'integer':
            $rules[] = 'integer';
                break;
            case 'number':
                $rules[] = 'numeric';
                    break;
            case 'boolean':
                $rules[] = 'boolean';
                    break;
        }
        return $rules;
    }
}
