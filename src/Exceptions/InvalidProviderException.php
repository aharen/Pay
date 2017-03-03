<?php

namespace aharen\Pay\Exceptions;

class InvalidProviderException extends RuntimeException
{
    public function __construct($message = "Invalid Payment Provider", $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
