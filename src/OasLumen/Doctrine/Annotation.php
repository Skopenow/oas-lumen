<?php

namespace DanBallance\OasLumen\Doctrine;

class Annotation
{
    protected $class;
    protected $params = [];

    public function __construct(string $class, array $params = [])
    {
        $this->class = $class;
        $this->params = $params;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getParamsAsString()
    {
        return $this->parseArray($this->params, false);
    }

    protected function parseArray(
        array $array, 
        bool $inArray
    ) {
        $bracketOpen = $inArray ? '{' : '(';
        $bracketClose = $inArray ? '}' : ')';
        $params = [];
        foreach ($array as $name => $val) {
            $params[] = $this->paramToString($name, $val, $inArray);
        }
        return $bracketOpen . implode(', ', $params) . $bracketClose;   
    }

    protected function paramToString(
        string $name,
        $val,
        bool $inArray
    ) {
        $quote = $inArray ? '"' : '';
        $separator = $inArray ? ':' : '=';
        $param = "{$quote}{$name}{$quote}{$separator}";
        if (is_int($val)) {
            $param .= "{$val}";
        } elseif (is_string($val)) {
            $param .= "\"{$val}\"";
        } elseif (is_bool($val)) {
            $param .= $val ? 'true' : 'false';
        } elseif (is_array($val)) {
            $param .= $this->parseArray($val, true);
        }
        return $param;
    }

    public function getNamespace()
    {
        return "ORM";
    }

    public function __toString()
    {
        $string = strtr(
            "@:namespace\:class",
            [
                ':namespace' => $this->getNamespace(),
                ':class' => $this->getClass()
            ]
        );
        if ($this->getParams()) {
            $string .= $this->getParamsAsString();
        }
        return $string;
    }
}
