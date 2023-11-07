<?php

namespace DanBallance\OasLumen\Doctrine;

use Psr\Http\Message\ServerRequestInterface;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Support\Collection;
use DanBallance\OasTools\specification\Fragments\FragmentInterface;
use DanBallance\OasLumen\Doctrine\EntityBuilder;
use DanBallance\OasLumen\Doctrine\CriteriaBuilder;
use DanBallance\OasLumen\Specification;
use DanBallance\OasLumen\Interfaces\StorageInterface;

class Storage implements StorageInterface
{
    private $specification;
    private $entityManager;

    public function __construct(
        Specification $specification,
        EntityManagerInterface $entityManager
    ) {
        $this->specification = $specification;
        $this->entityManager = $entityManager;
    }

    public function findOne(
        ServerRequestInterface $request,
        array $routeParams,
        FragmentInterface $operation,
        string $schemaName
    ): ?object {
        $schema = $this->specification->getSchema($schemaName, true);
        $criteria = (new CriteriaBuilder($this->specification))
            ->make($request, $routeParams, $operation, $schema);
        $entities = $this->entityManager
            ->getRepository("\\App\\Entities\\{$schemaName}")
            ->matching($criteria);
        if ($entities->isEmpty()) {
            return null;
        }
        return $entities->first();
    }

    public function findCollection(
        ServerRequestInterface $request,
        array $routeParams,
        FragmentInterface $operation,
        string $schemaName
    ): Collection {
        $schema = $this->specification->getSchema($schemaName, true);
        $criteria = (new CriteriaBuilder($this->specification))
            ->make($request, $routeParams, $operation, $schema);
        $collection =  $this->entityManager
            ->getRepository("\\App\\Entities\\{$schemaName}")
            ->matching($criteria);
        return new Collection($collection->toArray());
    }

    public function update(
        object $resource,
        ServerRequestInterface $request,
        array $routeParams,
        FragmentInterface $operation,
        string $schemaName
    ): object {
        $schema = $this->specification->getSchema($schemaName, true);
        $requestBody = json_decode($request->getBody()->__toString(), true);
        $entityBuilder = new EntityBuilder($schemaName, $schema);
        $resource = $entityBuilder->update($resource, $requestBody);
        $this->entityManager->persist($resource);
        $this->entityManager->flush();
        return $resource;
    }

    public function delete(
        object $resource,
        ServerRequestInterface $request,
        array $routeParams,
        FragmentInterface $operation,
        string $schemaName
    ): void {
        $this->entityManager->remove($resource);
        $this->entityManager->flush();
    }

    public function create(
        ServerRequestInterface $request,
        array $routeParams,
        FragmentInterface $operation,
        string $schemaName
    ): object {
        $schema = $this->specification->getSchema($schemaName, true);
        $requestBody = json_decode($request->getBody()->__toString(), true);
        $entityBuilder = new EntityBuilder($schemaName, $schema);
        $resource = $entityBuilder->make($requestBody);
        $this->entityManager->persist($resource);
        $this->entityManager->flush();
        return $resource;
    }
}
