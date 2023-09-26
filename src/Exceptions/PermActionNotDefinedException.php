<?php

namespace Lkt\Exceptions;

use Exception;

class PermActionNotDefinedException extends Exception
{
    public function __construct($message = '', $val = 0, Exception $old = null)
    {
        $message = "PermActionNotDefinedException: Perm action '{$message}' not defined";
        parent::__construct($message, $val, $old);
    }

    public static function getInstance(string $message): static
    {
        return new static($message);
    }
}