<?php

namespace DanBallance\OasLumen\Tests;

use PHPUnit\Framework\TestCase;
use Illuminate\Http\Request;
use DanBallance\OasLumen\Specification;
use DanBallance\OasLumen\Exceptions\JsonErrorFactory;
use DanBallance\OasLumen\Http\Psr7Factory;
use DanBallance\OasLumen\Http\HeaderFactory;
use DanBallance\OasLumen\Http\HeaderValueFactory;
use DanBallance\OasLumen\Http\RequestHeadersFactory;
use DanBallance\OasLumen\Middleware\ContentTypeNegotiation;

class ContentTypeNegotiationTest extends TestCase
{
    /**
     * @see https://tools.ietf.org/html/rfc7231#section-5.3.2
     * 
     * "A request without any Accept header field implies that the user agent
     * will accept any media type in response."
     * 
     */
    public function testNoAcceptHeaderIsAcceptable()
    {
        $middleware = $this->makeMiddleware();
        $request = Request::create('/player', 'GET', []);
        $request->headers->remove('Accept');
        $result = $middleware->handle(
            $request,
            function ($request) {
                return $request;
            },
            'crudl.list'
        );
        $this->assertInstanceOf(
            Request::class,
            $result
        );
    }

    public function testCompletelyWildcardAcceptHeaderIsAcceptable()
    {
        $middleware = $this->makeMiddleware();
        $request = Request::create('/player', 'GET', []);
        $request->headers->set('Accept', '*/*');
        $result = $middleware->handle(
            $request,
            function ($request) {
                return $request;
            },
            'crudl.list'
        );
        $this->assertInstanceOf(
            Request::class,
            $result
        );
    }

    public function testWildcardSubtypeAcceptHeaderIsAcceptable()
    {
        $middleware = $this->makeMiddleware();
        $request = Request::create('/player', 'GET', []);
        $request->headers->set('Accept', 'application/*');
        $result = $middleware->handle(
            $request,
            function ($request) {
                return $request;
            },
            'crudl.list'
        );
        $this->assertInstanceOf(
            Request::class,
            $result
        );
    }

    public function testFullAcceptHeaderIsAcceptable()
    {
        $middleware = $this->makeMiddleware();
        $request = Request::create('/player', 'GET', []);
        $request->headers->set('Accept', 'application/json');
        $result = $middleware->handle(
            $request,
            function ($request) {
                return $request;
            },
            'crudl.list'
        );
        $this->assertInstanceOf(
            Request::class,
            $result
        );
    }

    public function testUnacceptableMimeTypeReturns406()
    {
        $middleware = $this->makeMiddleware();
        $request = Request::create('/player', 'GET', []);
        $request->headers->set('Accept', 'text/plain');
        $response = $middleware->handle(
            $request,
            function ($request) {
                return $request;
            },
            'crudl.list'
        );
        $this->assertEquals(
            406,
            $response->getStatusCode()
        );
    }

    public function testUnacceptableWildcardMineTypeReturns406()
    {
        $middleware = $this->makeMiddleware();
        $request = Request::create('/player', 'GET', []);
        $request->headers->set('Accept', 'text/*');
        $response = $middleware->handle(
            $request,
            function ($request) {
                return $request;
            },
            'crudl.list'
        );
        $this->assertEquals(
            406,
            $response->getStatusCode()
        );
    }

    public function testUnsupportedContentTypeReturns415()
    {
        $middleware = $this->makeMiddleware();
        $request = Request::create(
            '/player',
            'POST',
            $parameters = [],
            $cookies = [],
            $files = [],
            $server = [],
            $content = '<?xml version="1.0" encoding="UTF-8" ?></xml>'
        );
        $request->headers->set('Accept', 'application/json');
        $request->headers->set('Content-Type', 'application/xml');
        $response = $middleware->handle(
            $request,
            function ($request) {
                return $request;
            },
            'crudl.create'
        );
        $this->assertEquals(
            415,
            $response->getStatusCode()
        );
    }

    protected function makeMiddleware()
    {
        $path = dirname(__FILE__)  . '/fixtures/specifications/oas3/player.yml';
        $requestHeadersFactory = new RequestHeadersFactory(
            new HeaderFactory(
                new HeaderValueFactory()
            )
        );
        return new ContentTypeNegotiation(
            Specification::fromFile($path),
            new JsonErrorFactory(),
            new Psr7Factory(),
            $requestHeadersFactory
        );
    }
}
