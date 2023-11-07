<?php

namespace DanBallance\OasLumen\Exceptions;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use DanBallance\OasLumen\Exceptions\JsonErrorFactory;

class Handler extends ExceptionHandler
{
    protected $jsonErrorFactory;

    public function __construct(JsonErrorFactory $factory)
    {
        $this->jsonErrorFactory = $factory;
    }

    public function render(
        $request,
        Exception $e
    ) : JsonResponse {
        $parentRender = parent::render($request, $e);
        if ($parentRender instanceof JsonResponse) {
            return $parentRender;
        }
        $statusCode = $parentRender->status();
        $detail = null;
        if ($e instanceof HttpException && $e->getMessage()) {
            $detail = $e->getMessage();
        }
        return $this->jsonErrorFactory->make($statusCode, $detail);
    }
}
