<?php

namespace DanBallance\OasLumen\Controllers;

use Exception;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Http\Response;

class CrudlController extends BaseController {
    public function create(
        ServerRequestInterface $request,
        ...$routeParams
    ) : ResponseInterface {
        [$reqSchemaName, $respSchemaName, $operation] = $this->lookup('create');
        $validationResult = $this->validate->request(
            $request,
            $routeParams,
            $operation,
            $reqSchemaName               
        );
        if ($validationResult->hasErrors()) {
            return $this->serialize->error(
                $request,
                'Bad Request',
                400,
                implode(' ', $validationResult->getErrors())
            );
        }
        $resource = $this->storage->create(
            $request,
            $routeParams,
            $operation,
            $respSchemaName
        );
        return $this->serialize->resource(
            $request,
            $resource,
            $operation,
            $respSchemaName,
            $statusCode = 201
        );
    }

    public function read(
        ServerRequestInterface $request,
        ...$routeParams
    ) : ResponseInterface {
        [$reqSchemaName, $respSchemaName, $operation] = $this->lookup('read');
        $resource = $this->storage->findOne(
            $request,
            $routeParams,
            $operation,
            $respSchemaName
        );
        if (!$resource) {
            return $this->serialize->error(
                $request,
                'Not Found',
                404,
                'Resource not found.'
            );
        }
        return $this->serialize->resource(
            $request,
            $resource,
            $operation,
            $respSchemaName
        );
    }

    public function update(
        ServerRequestInterface $request,
        ...$routeParams
    ) : ResponseInterface {
        [$reqSchemaName, $respSchemaName, $operation] = $this->lookup('update');
        $validationResult = $this->validate->request(
            $request,
            $routeParams,
            $operation,
            $reqSchemaName               
        );
        if ($validationResult->hasErrors()) {
            return $this->serialize->error(
                $request,
                'Bad Request',
                400,
                implode(' ', $validationResult->getErrors())
            );
        }
        $resource = $this->storage->findOne(
            $request,
            $routeParams,
            $operation,
            $respSchemaName
        );
        if (!$resource) {
            return $this->serialize->error(
                $request,
                'Not Found',
                404,
                'Resource not found.'
            );
        }
        $resource = $this->storage->update(
            $resource,
            $request,
            $routeParams,
            $operation,
            $respSchemaName
        );
        return $this->serialize->resource(
            $request,
            $resource,
            $operation,
            $respSchemaName
        );
    }

    public function delete(
        ServerRequestInterface $request,
        ...$routeParams
    ) : ResponseInterface {
        [$reqSchemaName, $respSchemaName, $operation] = $this->lookup('delete');
        $resource = $this->storage->findOne(
            $request,
            $routeParams,
            $operation,
            $respSchemaName
        );
        if ($resource) {
            $this->storage->delete(
                $resource,
                $request,
                $routeParams,
                $operation,
                $reqSchemaName
            );
        }
        $response = Response(
            null,
            204
        );
        return $this->psr7Factory->responseFromLumen($response);
    }

    public function list(
        ServerRequestInterface $request,
        ...$routeParams
    ) : ResponseInterface {
        [$reqSchemaName, $respSchemaName, $operation] = $this->lookup('list');
        $collection = $this->storage->findCollection(
            $request,
            $routeParams,
            $operation,
            $respSchemaName
        );
        return $this->serialize->collection(
            $request,
            $collection->toArray(),
            $operation,
            $respSchemaName
        );
    }
}
