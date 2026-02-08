<?php

namespace Cheney\adminSystem\Exceptions;

use Exception;

class AuthException extends Exception
{
    protected $code = 20000;

    public function __construct($message = '认证失败')
    {
        parent::__construct($message, $this->code);
    }
}