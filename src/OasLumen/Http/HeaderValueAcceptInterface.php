<?php

namespace DanBallance\OasLumen\Http;

interface HeaderValueAcceptInterface extends HeaderValueInterface
{
    public function mime() : string;
    public function type() : string;
    public function subType() : string;
    /**
     * Weight from 0.0 to 1.0 where higher values are of greater importance.
     * By default headers have a weight of 1.0.
     */
    public function weight() : float;
}
