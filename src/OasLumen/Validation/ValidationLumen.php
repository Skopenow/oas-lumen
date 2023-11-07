<?php

namespace DanBallance\OasLumen\Validation;

use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Validation\Validator;
use Illuminate\Validation\Factory;
use DanBallance\OasTools\specification\Fragments\FragmentInterface;
use DanBallance\OasLumen\Interfaces\ValidationInterface;
use DanBallance\OasLumen\Interfaces\ValidationResultInterface;
use DanBallance\OasLumen\Validation\ValidationLumen;
use DanBallance\OasLumen\Validation\ValidationResult;
use DanBallance\OasLumen\Validation\SchemaValidator;

class ValidationLumen implements ValidationInterface
{
    private $schemaValidator;
    
    public function __construct(
        SchemaValidator $schemaValidator,
        Factory $factory
    ) {
        $this->schemaValidator = $schemaValidator;
        $this->factory = $factory;
    }

    public function request(
        ServerRequestInterface $request,
        array $routeParams,
        FragmentInterface $operation,
        string $schemaName             
    ) : ValidationResultInterface {
        [$data, $rules] = $this->schemaValidator->setup(
            $request,
            $routeParams,
            $operation,
            $schemaName
        );
        $validator = $this->factory->make(
            $data,
            $rules
        );

        if ($validator->fails()) {
            $errors = array_map(
                function($errors){         
                    foreach($errors as $key=>$value){
                        return $value;                           
                    }                       
                },
                $validator->errors()->toArray()
            );
            return new ValidationResult(false, $errors);       
        } else {
            return new ValidationResult(true);
        }
    }
}
