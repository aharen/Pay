<?php

namespace aharen\Pay\Exceptions;

class SignatureMissmatchException extends RuntimeException
{
    public function __construct($message = "Response signature is not valid", $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
