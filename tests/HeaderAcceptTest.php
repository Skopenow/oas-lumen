<?php

namespace DanBallance\OasLumen\Tests;

use PHPUnit\Framework\TestCase;
use DanBallance\OasLumen\Tests\Traits\MockRequestsHelper;
use DanBallance\OasLumen\Http\RequestHeaders;
use DanBallance\OasLumen\Http\HeaderInterface;
use DanBallance\OasLumen\Http\HeaderValueInterface;
use DanBallance\OasLumen\Http\HeaderFactory;
use DanBallance\OasLumen\Http\HeaderValueFactory;
use DanBallance\OasLumen\Http\HeaderValueAcceptInterface;

class HeaderAcceptTest extends TestCase
{
    use MockRequestsHelper;

    public function testAcceptHeaderSingleMediaRangeNoParams()
    {
        $request = $this->makeRequest()
            ->withHeader('Accept', 'application/json');
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
            HeaderValueAcceptInterface::class,
            $header->values()
        );
        $this->assertEquals(
            'accept',
            $header->name()
        );
        $this->assertEquals(
            'Accept',
            $header->originalName()
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

    public function testAcceptHeaderTwoMediaRangesNoParams()
    {
        $request = $this->makeRequest()
            ->withHeader('Accept', 'application/json,application/xml');
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
            HeaderValueAcceptInterface::class,
            $header->values()
        );
        $this->assertEquals(
            'accept',
            $header->name()
        );
        $this->assertEquals(
            'Accept',
            $header->originalName()
        );
        $this->assertCount(
            2,
            $header->values()
        );
        $value1 = $header->values()[0];
        $value2 = $header->values()[1];
        $this->assertEquals(
            'application/json',
            (string) $value1
        );
        $this->assertEquals(
            'application/xml',
            (string) $value2
        );
        $this->assertEquals(
            'application/json',
            $value1->value()
        );
        $this->assertEquals(
            'application/xml',
            $value2->value()
        );
        $this->assertEquals(
            'application',
            $value1->type()
        );
        $this->assertEquals(
            'application',
            $value2->type()
        );
        $this->assertEquals(
            'json',
            $value1->subType()
        );
        $this->assertEquals(
            'xml',
            $value2->subType()
        );
        $this->assertEquals(
            1.0,
            $value1->weight()
        );
        $this->assertEquals(
            1.0,
            $value2->weight()
        );
        $this->assertEquals(
            [],
            $value1->params()
        );
        $this->assertEquals(
            [],
            $value2->params()
        );
    }

    public function testAcceptHeaderTwoMediaRangesWithQParams()
    {
        $request = $this->makeRequest()
            ->withHeader('Accept', 'application/json;q=0.5, application/xml;q=0.8');
        $headerFactory = new HeaderFactory(
            new HeaderValueFactory()
        );
        $requestHeaders = new RequestHeaders(
            $headerFactory,
            $request
        );
        $header = $requestHeaders->get('Accept');
        $this->assertCount(
            2,
            $header->values()
        );
        $value1 = $header->values()[0];
        $value2 = $header->values()[1];
        $this->assertEquals(
            'application/xml;q=0.8',
            (string) $value1
        );
        $this->assertEquals(
            'application/json;q=0.5',
            (string) $value2
        );
        $this->assertEquals(
            'application/xml',
            $value1->value()
        );
        $this->assertEquals(
            'application/json',
            $value2->value()
        );
        $this->assertEquals(
            0.8,
            $value1->weight()
        );
        $this->assertEquals(
            0.5,
            $value2->weight()
        );
        $this->assertEquals(
            [],
            $value1->params()
        );
        $this->assertEquals(
            [],
            $value2->params()
        );
    }

    public function testAcceptHeaderTwoMediaRangesWithoutQParams()
    {
        $request = $this->makeRequest()
            ->withHeader(
                'Accept',
                'text/*, text/plain, text/plain;format=flowed, */*'
            );
        $headerFactory = new HeaderFactory(
            new HeaderValueFactory()
        );
        $requestHeaders = new RequestHeaders(
            $headerFactory,
            $request
        );
        $header = $requestHeaders->get('Accept');
        $this->assertCount(
            4,
            $header->values()
        );
        $this->assertEquals(
            [
                'text/plain;format=flowed',
                'text/plain',
                'text/*',
                '*/*',
            ],
            [
                (string) $header->values()[0],
                (string) $header->values()[1],
                (string) $header->values()[2],
                (string) $header->values()[3]
            ]
        );
        $this->assertEquals(
            [
                'text/plain',
                'text/plain',
                'text/*',
                '*/*',
            ],
            [
                $header->values()[0]->mime(),
                $header->values()[1]->mime(),
                $header->values()[2]->mime(),
                $header->values()[3]->mime()
            ]
        );
    }

    public function testAcceptHeaderMultipleMediaRangesOrderedByWeight()
    {
        $request = $this->makeRequest()
            ->withHeader(
                'Accept',
                'text/*;q=0.3, text/html;q=0.7, text/html;level=1, text/html;level=2;q=0.4, */*;q=0.5'
            );
        $headerFactory = new HeaderFactory(
            new HeaderValueFactory()
        );
        $requestHeaders = new RequestHeaders(
            $headerFactory,
            $request
        );
        $header = $requestHeaders->get('Accept');

        $this->assertCount(
            5,
            $header->values()
        );
        $this->assertEquals(
            [
                'text/html;level=1',
                'text/html;q=0.7',
                '*/*;q=0.5',
                'text/html;level=2;q=0.4',
                'text/*;q=0.3',
            ],
            [
                (string) $header->values()[0],
                (string) $header->values()[1],
                (string) $header->values()[2],
                (string) $header->values()[3],
                (string) $header->values()[4]
            ]
        );
        $this->assertEquals(
            [
                'text/html',
                'text/html',
                '*/*',
                'text/html',
                'text/*',
            ],
            [
                $header->values()[0]->mime(),
                $header->values()[1]->mime(),
                $header->values()[2]->mime(),
                $header->values()[3]->mime(),
                $header->values()[4]->mime()
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
