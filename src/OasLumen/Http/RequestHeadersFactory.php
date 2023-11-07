<?php

namespace DanBallance\OasLumen\Http;

use Psr\Http\Message\ServerRequestInterface;

class RequestHeadersFactory
{
    protected $headerFactory;

    public function __construct(HeaderFactory $headerFactory)
    {
        $this->headerFactory = $headerFactory;
    }

    public function make(ServerRequestInterface $request)
    {
        return new RequestHeaders(
            $this->headerFactory,
            $request
        );
    }
}
