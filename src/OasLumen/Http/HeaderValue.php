<?php

namespace DanBallance\OasLumen\Http;

class HeaderValue implements HeaderValueInterface
{
    protected $valueFull;
    protected $value;
    protected $params = [];
    
    public function __construct(string $value)
    {
        $this->valueFull = $value;
        if (stripos($value, ';') === false) {
            $this->value = $value;
            $this->params = [];
        } else {
            $params = explode(';', $value);
            $this->value = array_shift($params);
            $this->params = array_reduce(
                $params,
                function ($accumulator, $param) {
                    [$key, $value] = explode('=', $param);
                    $accumulator[trim($key)] = trim($value);
                    return $accumulator;
                },
                []
            );
        }
    }

    /**
     * Returns the full values, including prameters, i.e.:
     * 'Some value; param=val'
     */
    public function valueFull() : string
    {
        return $this->valueFull;
    }

    /**
     * Returns just the value portion of the string, without parameters, i.e.:
     * 'Some value'
     */
    public function value() : string
    {
        return $this->value;
    }

    public function __toString() : string
    {
        return $this->valueFull();
    }

    public function params() : array
    {
        return $this->params;
    }

    public function compare(HeaderValueInterface $b) : int
    {
        return -1;  // natural array order
    }
}
