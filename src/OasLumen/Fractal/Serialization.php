<?php

namespace DanBallance\OasLumen\Fractal;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Stream;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\SerializerAbstract;
use DanBallance\OasTools\specification\Fragments\FragmentInterface;
use DanBallance\OasLumen\Serializers\HalJsonSerializer;
use DanBallance\OasLumen\Serializers\PlainJsonSerializer;
use DanBallance\OasLumen\Fractal\DynamicTransformer;
use DanBallance\OasLumen\Specification;
use DanBallance\OasLumen\Http\RequestHeadersFactory;
use Danballance\OasLumen\Interfaces\SerializationInterface;

class Serialization implements SerializationInterface
{
    private $specification;
    private $availableSerializers = [];

    public function __construct(
        Specification $specification,
        RequestHeadersFactory $requestHeadersFactory
    ) {
        $this->specification = $specification;
        $this->requestHeadersFactory = $requestHeadersFactory;
        $this->availableSerializers = [
            'application/json' => PlainJsonSerializer::class,
            'application/hal+json' => HalJsonSerializer::class,
        ];  // @TODO these should be loaded from the application bootstrap
    }

    public function resource(
        ServerRequestInterface $request,
        object $resource,
        FragmentInterface $operation,
        string $schemaName,
        int $statusCode = 200
    ) : ResponseInterface {
        $transformer = new DynamicTransformer($this->specification, $schemaName);
        $resource = new Item($resource, $transformer, $schemaName);
        $serializer = $this->getSerializer($request, $operation);
        if (!$serializer) {
            return $this->error(
                $request,
                'Not Acceptable',
                406,
                'No serializer found.'
            );
        }
        $manager = new Manager;
        $manager->setSerializer($serializer);
        $payload = $manager->createData($resource)->toArray();
        return $this->response(
            $payload,
            $statusCode,
            [
                'Content-Type' => $serializer->getContentType()
            ]
        );
    }

    public function collection(
        ServerRequestInterface $request,
        array $collection,
        FragmentInterface $operation,
        string $schemaName,
        int $statusCode = 200
    ) : ResponseInterface {
        $transformer = new DynamicTransformer($this->specification, $schemaName);
        $resource = new Collection($collection, $transformer, $schemaName);
        $serializer = $this->getSerializer($request, $operation);
        if (!$serializer) {
            return $this->error(
                $request,
                'Not Acceptable',
                406,
                'No serializer found.',
            );
        }
        $manager = new Manager;
        $manager->setSerializer($serializer);
        $payload = $manager->createData($resource)->toArray();
        return $this->response(
            $payload,
            $statusCode,
            [
                'Content-Type' => $serializer->getContentType()
            ]
        );
    }

    public function error(
        ServerRequestInterface $request,
        string $title,
        int $statusCode = 500,
        string $detail = null,
        string $type = null,
        string $instance = null
    ) : ResponseInterface {
        $payload = [
            'title' => $title,
            'status' => $statusCode
        ];
        if ($detail) {
            $payload['detail'] = $detail;
        }
        if ($type) {
            $payload['type'] = $type;
        }
        if ($instance) {
            $payload['instance'] = $instance;
        }
        return $this->response(
            $payload,
            $statusCode,
            [
                'Content-Type' => 'application/json'
            ]
        );
    }

    protected function getSerializer(
        ServerRequestInterface $request,
        FragmentInterface $operation
    ) : ?SerializerAbstract {
        $requestHeaders = $this->requestHeadersFactory->make($request);
        $responseContentTypes = $operation->getResponseContentTypes();
        $acceptableContentType = $requestHeaders->mostAcceptableMimeType(
            $responseContentTypes
        );
        if (!array_key_exists($acceptableContentType, $this->availableSerializers)) {
            return null;
        }
        $serializerClass = $this->availableSerializers[$acceptableContentType];
        $basePath = 'http://localhost:8000';  // @TODO get from Request
        return new $serializerClass($basePath);
    }

    protected function response($payload, $statusCode = 200, $headers = [])
    {
        $stream = new Stream('php://temp', 'wb+');
        $stream->write(json_encode(
            $payload,
            JSON_UNESCAPED_SLASHES
        ));
        $stream->rewind();
        return new Response(
            $stream,
            $statusCode,
            $headers
        );
    }
}
