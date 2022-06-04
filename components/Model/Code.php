<?php

namespace Application\Model;

class Code
{
    private string $value;

    public function __construct($length)
    {
        $code = "";

        for ( $i = 0; $i < $length; $i++) {
            $code .= mt_rand(0,9);
        }

        $this->value = $code;
    }

    public function getValue()
    {
        return $this->value;
    }

}
