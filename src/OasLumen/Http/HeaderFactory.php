<?php

namespace DanBallance\OasLumen\Http;

use Psr\Http\Message\ServerRequestInterface;

class HeaderFactory
{
    protected $headerValueFactory;
    
    public function __construct(HeaderValueFactory $headerValueFactory)
    {
        $this->headerValueFactory = $headerValueFactory;
    }

    public function make(string $fieldName, string $fieldValue)
    {
        return new Header($this->headerValueFactory, $fieldName, $fieldValue);
    }
}
