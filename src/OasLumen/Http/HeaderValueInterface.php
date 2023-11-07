<?php

namespace DanBallance\OasLumen\Http;

interface HeaderValueInterface
{
    public function value() : string;
    public function __toString() : string;
    public function params() : array;
    /**
     * Used for sorting header values.
     * Each HeaderValueInterface instance encapuslates its own logic the sort.
     * The instance itself is $a in the usort function and the parameter is $b.
     */
    public function compare(HeaderValueInterface $b) : int;
}
