<?php

namespace DanBallance\OasLumen\Http;

class HeaderValueFactory
{
    public function make($fieldName, $value)
    {
        if (substr($fieldName, 0, 6) == 'accept') {
            return new HeaderValueAccept($value);
        }
        return new HeaderValue($value);
    }
}
