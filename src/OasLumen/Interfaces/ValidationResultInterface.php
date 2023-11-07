<?php

namespace DanBallance\OasLumen\Interfaces;

interface ValidationResultInterface
{
    public function hasErrors() : bool;
    public function getErrors() : array;
}
