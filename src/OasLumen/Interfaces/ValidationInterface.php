<?php

namespace DanBallance\OasLumen\Interfaces;

use Psr\Http\Message\ServerRequestInterface;
use DanBallance\OasTools\specification\Fragments\FragmentInterface;

interface ValidationInterface
{
    public function request(
        ServerRequestInterface $request,
        array $routeParams,
        FragmentInterface $operation,
        string $schemaName             
    ) : ValidationResultInterface;
}
