<?php

namespace DanBallance\OasLumen\Http;

use Exception;

class HeaderValueAccept extends HeaderValue implements 
    HeaderValueAcceptInterface
{
    protected $weight = 1.0;

    public function __construct(string $value)
    {
        parent::__construct($value);
        if (stripos($this->mime(), '/') === false) {
            throw new Exception(
                'Accept header must have a mime type of format: type/subType. ' .
                'For example text/html.'
            );
        }
        if (isset($this->params['q'])) {
            $this->weight = (float) $this->params['q'];
            unset($this->params['q']);
        }
    }

    public function mime() : string
    {
        return $this->value;
    }

    public function type() : string
    {
        return explode('/', $this->value)[0];
    }

    public function subType() : string
    {
        return explode('/', $this->value)[1];
    }

    public function weight() : float
    {
        return $this->weight;
    }

    public function compare(HeaderValueInterface $b) : int
    {
        // 1) q weight has highest priority
        if ($this->weight() != $b->weight()) {
            return ($this->weight() < $b->weight()) ? 1 : -1;
        }
        // 2) specificity - wildcards
        $wildCountThis = substr_count($this->value(), '*');
        $wildCountB = substr_count($b->value(), '*');
        if ($wildCountThis != $wildCountB) {
            return ($wildCountThis < $wildCountB) ? -1 : 1;
        }       
        // 3) specificity - additional params
        $specificityThis = count($this->params());
        $specificityB = count($b->params());
        if ($this->value() == $b->value() && $specificityThis == $specificityB ) {
            return ($specificityThis < $specificityB) ? -1 : 1;
        }
        return 0;
    }
}
