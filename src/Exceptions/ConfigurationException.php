<?php

namespace aharen\Pay\Exceptions;

class ConfigurationException extends RuntimeException
{
    public function __construct($message = "Configuration Error", $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
