<?php

namespace DanBallance\OasLumen\Tests;

use PHPUnit\Framework\TestCase;
use DanBallance\OasLumen\Tests\Traits\MockRequestsHelper;
use DanBallance\OasLumen\Http\RequestHeaders;
use DanBallance\OasLumen\Http\HeaderInterface;
use DanBallance\OasLumen\Http\HeaderFactory;
use DanBallance\OasLumen\Http\HeaderValueFactory;
use DanBallance\OasLumen\Http\HeaderValueInterface;

class HeaderTest extends TestCase
{
    use MockRequestsHelper;

    public function testSimpleHeader()
    {
        $request = $this->makeRequest()
            ->withHeader('Referer', 'https://domain.com/');
        $headerFactory = new HeaderFactory(
            new HeaderValueFactory()
        );
        $requestHeaders = new RequestHeaders($headerFactory, $request);
        $header = $requestHeaders->get('Referer');
        $this->assertInstanceOf(
            HeaderInterface::class,
            $header
        );
        $this->assertContainsOnlyInstancesOf(
            HeaderValueInterface::class,
            $header->values()
        );
        $this->assertContainsOnlyInstancesOf(
            HeaderValueInterface::class,
            $header->values()
        );
        $this->assertEquals(
            'referer',
            $header->name()
        );
        $this->assertEquals(
            'Referer',
            $header->originalName()
        );
        $this->assertEquals(
            'Referer: https://domain.com/',
            $header->line()
        );
        $this->assertEquals(
            'Referer: https://domain.com/',
            (string) $header
        );
        $this->assertEquals(
            'https://domain.com/',
            $header->value()
        );
        $this->assertCount(
            1,
            $header->values()
        );
        $this->assertEquals(
            'https://domain.com/',
            (string) $header->values()[0]
        );
        $this->assertEquals(
            'https://domain.com/',
            $header->values()[0]->value()
        );
        $this->assertEquals(
            [],
            $header->values()[0]->params()
        );
    }

    public function testContentTypeHeaderWithParameter()
    {
        $request = $this->makeRequest()
            ->withHeader('Content-Type', 'text/html; charset=UTF-8');
        $headerFactory = new HeaderFactory(
            new HeaderValueFactory()
        );
        $requestHeaders = new RequestHeaders(
            $headerFactory,
            $request
        );
        $header = $requestHeaders->get('Content-Type');
        $this->assertInstanceOf(
            HeaderInterface::class,
            $header
        );
        $this->assertContainsOnlyInstancesOf(
            HeaderValueInterface::class,
            $header->values()
        );
        $this->assertEquals(
            'content-type',
            $header->name()
        );
        $this->assertEquals(
            'Content-Type',
            $header->originalName()
        );
        $this->assertEquals(
            'Content-Type: text/html; charset=UTF-8',
            $header->line()
        );
        $this->assertEquals(
            'text/html; charset=UTF-8',
            $header->value()
        );
        $this->assertCount(
            1,
            $header->values()
        );
        $this->assertEquals(
            'text/html; charset=UTF-8',
            (string) $header->values()[0]
        );
        $this->assertEquals(
            'text/html',
            $header->values()[0]->value()
        );
        $this->assertEquals(
            [
                'charset' => 'UTF-8'
            ],
            $header->values()[0]->params()
        );
    }

    public function testAcceptHeaderTwoMediaRangesNoParams()
    {
        $request = $this->makeRequest()
            ->withHeader('X-Test', 'paramOne,paramTwo');
        $headerFactory = new HeaderFactory(
            new HeaderValueFactory()
        );
        $requestHeaders = new RequestHeaders(
            $headerFactory,
            $request
        );
        $header = $requestHeaders->get('X-Test');
        $this->assertCount(
            2,
            $header->values()
        );
        $this->assertEquals(
            [
                'paramOne',
                'paramTwo'
            ],
            [
                (string) $header->values()[0],
                (string) $header->values()[1]
            ]
        );

    }

    protected function makeRequest()
    {
        return $this->mockRequest(
            'get',
            '/resources/124'
        );
    }
}
