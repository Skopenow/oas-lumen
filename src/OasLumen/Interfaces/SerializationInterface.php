<?php

namespace DanBallance\OasLumen\Interfaces; 

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Http\Response;
use DanBallance\OasTools\Specification\Fragments\FragmentInterface;

interface SerializationInterface
{
    public function resource(
        ServerRequestInterface $request,
        object $resource,
        FragmentInterface $operation,
        string $schemaName,
        int $statusCode = 200
    ): ResponseInterface;

    public function collection(
        ServerRequestInterface $request,
        array $collection,
        FragmentInterface $operation,
        string $schemaName,
        int $statusCode = 200
    ): ResponseInterface;

    /**
     * @see https://tools.ietf.org/html/rfc7807
     */
    public function error(
        ServerRequestInterface $request,
        string $title,
        int $statusCode = 500,
        string $detail = null,
        string $type = null,
        string $instance = null
    ): ResponseInterface;
}
