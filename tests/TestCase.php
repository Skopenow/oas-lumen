<?php

namespace DanBallance\OasLumen\Tests;

class TestCase extends \PHPUnit\Framework\TestCase
{

    /**
     * @see https://stackoverflow.com/a/49401666/5237002
     */
    protected function createRequest(
        $method,
        $content,
        $uri = '/test',
        $server = ['CONTENT_TYPE' => 'application/json'],
        $parameters = [],
        $cookies = [],
        $files = []
    ) {
        $request = new \Illuminate\Http\Request;
        return $request->createFromBase(
            \Symfony\Component\HttpFoundation\Request::create(
                $uri,
                $method,
                $parameters,
                $cookies,
                $files,
                $server,
                $content
            )
        );
    }
}