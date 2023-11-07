<?php

namespace DanBallance\OasLumen\Tests;

use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Validation\Factory;
use DanBallance\OasLumen\Specification;
use DanBallance\OasLumen\Validation\SchemaValidator;
use DanBallance\OasLumen\Tests\Traits\MockRequestsHelper;

class SchemaValidatorTest extends TestCase
{
    use MockRequestsHelper;

    public function testSetup()
    {
        $path = dirname(__FILE__)  .
            '/fixtures/specifications/oas3/petstore-extended.yml';
        $spec = Specification::fromFile($path);
        $schemaValidator = new SchemaValidator($spec);
        $requestBody = [
            'name' => 'Bob',
            'tag' => 'cat'
        ];
        $request = $this->mockRequest(
            'post',
            'http://localhost/pets',
            [],
            $requestBody
        );
        [$data, $rules] = $schemaValidator->setup(
            $request,
            [],
            $spec->getOperation('addPet'),
            'NewPet'
        );
        $this->assertEquals(
            $requestBody,
            $data
        );
        $this->assertEquals(
            [
                'name' => 'string|required',
                'tag' => 'string'
            ],
            $rules
        );
    }
}
