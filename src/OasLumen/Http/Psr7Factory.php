<?php

namespace DanBallance\OasLumen\Http;

use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Psr7Factory
{
    public function requestFromLumen(Request $request)
    {
        $factory = new Psr17Factory();
        $httpFactory = new PsrHttpFactory($factory, $factory, $factory, $factory);
        return $httpFactory->createRequest($request);
    }

    public function responseFromLumen(Response $response)
    {
        $factory = new Psr17Factory();
        $httpFactory = new PsrHttpFactory($factory, $factory, $factory, $factory);
        return $httpFactory->createResponse($response);
    }
}
