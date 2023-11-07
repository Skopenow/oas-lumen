<?php

namespace DanBallance\OasLumen\Tests\Traits;

use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Uri;
use Zend\Diactoros\Stream;

trait MockRequestsHelper
{
    /**
     * @param array $headers headrName => headerContent
     */
    protected function mockRequest(
        string $method,
        string $uri,
        array $query = [],
        array $requestBody = null,
        array $headers = []
    ) {
        $uri = (new Uri($uri));
        if ($query) {
            $queryString = http_build_query($query);
            $uri = $uri->withQuery($queryString);
        }
        if ($requestBody) {
            $body = new Stream('php://memory', 'wb+');
        } else {
            $body = 'php://input';
        }
        $request = new ServerRequest(
            [],
            [],
            null,
            null,
            $body,
            $headers
        );
        if ($requestBody) {
            $request->getBody()->write(
                json_encode($requestBody)
            );
        }
        return $request->withUri($uri)->withMethod($method);
    }
}
