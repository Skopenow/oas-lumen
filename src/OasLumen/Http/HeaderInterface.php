<?php

namespace DanBallance\OasLumen\Http;

interface HeaderInterface
{
    /**
     * returns the lowercase field name
     */
    public function name() : string;

    /**
     * returns the field name as passed via the constructor
     */
    public function originalName() : string;

    /**
     * returns the full 'fieldName: fieldValue' header
     */
    public function line() : string;

    /**
     * returns the fieldValue portion of the header
     */
    public function value() : string;

    /**
     * returns the fieldValue portion of the header as an array of values
     * @return HeaderValueInterface[]
     */
    public function values() : array;

    /**
     * returns the full header as returned by line() when cast to string
     */
    public function __toString() : string;
}
