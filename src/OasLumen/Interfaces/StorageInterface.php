<?php

namespace DanBallance\OasLumen\Interfaces;

use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Support\Collection;
use DanBallance\OasTools\specification\Fragments\FragmentInterface;

interface StorageInterface
{
    public function findOne(
        ServerRequestInterface $request,
        array $routeParams,
        FragmentInterface $operation,
        string $schemaName
    ): ?object;

    public function findCollection(
        ServerRequestInterface $request,
        array $routeParams,
        FragmentInterface $operation,
        string $schemaName
    ): Collection;

    public function update(
        object $resource,
        ServerRequestInterface $request,
        array $routeParams,
        FragmentInterface $operation,
        string $schemaName
    ): object;

    public function delete(
        object $resource,
        ServerRequestInterface $request,
        array $routeParams,
        FragmentInterface $operation,
        string $schemaName
    ): void;

    public function create(
        ServerRequestInterface $request,
        array $routeParams,
        FragmentInterface $operation,
        string $schemaName
    ): object;
}
