<?php

namespace DanBallance\OasLumen\Tests;

use DanBallance\OasLumen\Specification;
use DanBallance\OasLumen\Doctrine\EntityBuilder;
use DanBallance\OasLumen\Validation\ValidationLumen;
use DanBallance\OasLumen\Validation\SchemaValidator;
use DanBallance\OasLumen\Tests\Traits\MockRequestsHelper;
use DanBallance\OasLumen\Tests\Traits\ValidationFactoryHelper;

class ValidationLumenTest extends TestCase
{
    use MockRequestsHelper;
    use ValidationFactoryHelper;

    public function testSetupValidRequest()
    {
        $path = dirname(__FILE__)  .
            '/fixtures/specifications/oas3/petstore-extended.yml';
        $spec = Specification::fromFile($path);
        $request = $this->mockRequest(
            'post',
            'http://localhost/pets',
            [],
            [
                'name' => 'Bob',
                'tag' => 'cat'
            ] 
        );
        $validationFactory = $this->makeValidationFactory();
        $validate = new ValidationLumen(
            new SchemaValidator($spec),
            $validationFactory
        );
        $validationResult = $validate->request(
            $request,
            [],
            $spec->getOperation('addPet'),
            'NewPet'             
        );
        $this->assertFalse(
            $validationResult->hasErrors()
        );
        $this->assertEquals(
            [],
            $validationResult->getErrors()
        );
    }

    public function testSetupInvalidRequest()
    {
        $path = dirname(__FILE__)  .
            '/fixtures/specifications/oas3/petstore-extended.yml';
        $spec = Specification::fromFile($path);
        $request = $this->mockRequest(
            'post',
            'http://localhost/pets',
            [],
            [
                'name' => 123,
                'tag' => 'cat'
            ] 
        );
        $validationFactory = $this->makeValidationFactory();
        $validate = new ValidationLumen(
            new SchemaValidator($spec),
            $validationFactory
        );
        $validationResult = $validate->request(
            $request,
            [],
            $spec->getOperation('addPet'),
            'NewPet'             
        );
        $this->assertTrue(
            $validationResult->hasErrors()
        );
        $this->assertEquals(
            [
                'name' => 'validation.string'  // not translated yet
            ],
            $validationResult->getErrors()
        );
    }
}
