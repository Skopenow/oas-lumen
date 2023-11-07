<?php

namespace DanBallance\OasLumen\Tests;

use PHPUnit\Framework\TestCase;
use DanBallance\OasLumen\Tests\Traits\MockRequestsHelper;
use DanBallance\OasLumen\Http\RequestHeaders;
use DanBallance\OasLumen\Http\HeaderInterface;
use DanBallance\OasLumen\Http\HeaderValueInterface;
use DanBallance\OasLumen\Http\HeaderFactory;
use DanBallance\OasLumen\Http\HeaderValueFactory;
use DanBallance\OasLumen\Http\HeaderValueAccept;

class RequestHeadersTest extends TestCase
{
    use MockRequestsHelper;

    public function testSimpleAcceptHeader()
    {
        $request = $this->makeRequestFromHeaders([
            'Accept' => 'application/json'
        ]);
        $headerFactory = new HeaderFactory(
            new HeaderValueFactory()
        );
        $requestHeaders = new RequestHeaders(
            $headerFactory,
            $request
        );
        $header = $requestHeaders->get('Accept');
        $this->assertInstanceOf(
            HeaderInterface::class,
            $header
        );
        $this->assertContainsOnlyInstancesOf(
            HeaderValueInterface::class,
            $header->values()
        );
        $this->assertContainsOnlyInstancesOf(
            HeaderValueAccept::class,
            $header->values()
        );
        $this->assertCount(
            1,
            $header->values()
        );
        $this->assertEquals(
            'application/json',
            (string) $header->values()[0]
        );
        $this->assertEquals(
            'application/json',
            $header->values()[0]->value()
        );
        $this->assertEquals(
            'application',
            $header->values()[0]->type()
        );
        $this->assertEquals(
            'json',
            $header->values()[0]->subType()
        );
        $this->assertEquals(
            1.0,
            $header->values()[0]->weight()
        );
        $this->assertEquals(
            [],
            $header->values()[0]->params()
        );
    }

    public function testMultiAcceptHeaderWithQPriorityOutOfSequence()
    {
        $request = $this->makeRequestFromHeaders([
            'Accept' => '*/*;q=0.8,text/html,application/xml;q=0.9,application/xhtml+xml'
        ]);
        $headerFactory = new HeaderFactory(
            new HeaderValueFactory()
        );
        $requestHeaders = new RequestHeaders(
            $headerFactory,
            $request
        );
        $header = $requestHeaders->get('Accept');
        $this->assertEquals(
            [
                'text/html',
                'application/xhtml+xml',
                'application/xml',
                '*/*',
            ],
            [
                $header->values()[0]->mime(),
                $header->values()[1]->mime(),
                $header->values()[2]->mime(),
                $header->values()[3]->mime(),
            ]
        );
    }

    protected function makeRequestFromHeaders(array $headers)
    {
        return $this->mockRequest(
            'get',
            '/resources/124',
            $query = [],
            $requestBody = [],
            $headers
        );
    }
}
