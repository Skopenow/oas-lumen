<?php

namespace DanBallance\OasLumen\Validation;

use DanBallance\OasLumen\Interfaces\ValidationResultInterface;

class ValidationResult implements ValidationResultInterface
{
    private $sucess;
    private $errors;

    public function __construct(bool $success, array $errors = [])
    {
        $this->success = $success;
        $this->errors = $errors;
    }

    public function hasErrors() : bool
    {
        return !$this->success;
    }

    public function getErrors() : array
    {
        return $this->errors;
    }

    public function setErrors(array $errors = [])
    {
        $this->errors = $errors;
    }

    public function setSuccess(bool $success)
    {
        $this->success = $success;
    }
}
