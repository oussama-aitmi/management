<?php

namespace App\Exception;

use Exception;

class ApiResponseException extends Exception
{
    /**
     * @param string $message [optional] The Exception message to throw.
     * @param int $code [optional] The Exception code.
     * @param $previous [optional] The previous throwable used for the exception chaining.
     */
    public function __construct($message = null, $code = 0, $previous=null)
    {
        parent::__construct($message, $code, $previous);
    }
}