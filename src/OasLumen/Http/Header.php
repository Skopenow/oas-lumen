<?php

namespace DanBallance\OasLumen\Http;

class Header implements HeaderInterface
{
    protected $headerValueFactory;
    protected $fieldName;
    protected $fieldValue;
    protected $values = [];

    public function __construct(
        HeaderValueFactory $headerValueFactory,
        string $fieldName,
        string $fieldValue
    ) {
        $this->headerValueFactory = $headerValueFactory;
        $this->originalFieldName = $fieldName;
        $this->fieldName = strtolower($fieldName);
        $this->fieldValue = $fieldValue;
    }

    /**
     * returns the lowercase field name
     */
    public function name() : string
    {
        return $this->fieldName;
    }

    /**
     * Unaltered field name as created.
     */
    public function originalName() : string
    {
        return $this->originalFieldName;
    }

    public function line() : string
    {
        return "{$this->originalFieldName}: {$this->fieldValue}";
    }

    public function value() : string
    {
        return $this->fieldValue;
    }

    public function __toString() : string
    {
        return $this->line();
    }

    /**
     * @return HeaderValueInterface[]
     */
    public function values() : array
    {
        if (!$this->values) {
            $sorted = [];  // [q][specificity][natural order]
            $values = array_map(
                function ($value) {
                    return $this->headerValueFactory->make(
                        $this->name(),
                        trim($value)
                    );
                },
                explode(',', $this->fieldValue)
            );
            usort(
                $values,
                function (HeaderValueInterface $a, HeaderValueInterface $b) {
                    return $a->compare($b);
                }
            );
            $this->values = $values;
        }
        return $this->values;
    }
}
