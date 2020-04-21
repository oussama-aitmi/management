<?php

namespace App\Response;

use Exception;

class ApiResponseException extends Exception
{
    /**
     * @var ApiResponse
     */
    private $ApiResponse;

    /**
     * @param ApiResponse $ApiResponse
     * @param string      $message [optional] The Exception message to throw.
     * @param int         $code [optional] The Exception code.
     * @param             $previous [optional] The previous throwable used for the exception chaining.
     */
    public function __construct(ApiResponse $ApiResponse, $message = null, $code = 0, $previous=null)
    {
        $this->ApiResponse = $ApiResponse;
        $code = $ApiResponse->getStatusCode();
        $message = $ApiResponse->getTitle();

        parent::__construct($message, $code, $previous);
    }

    public function getApiResponse(): ApiResponse
    {
        return $this->ApiResponse;
    }
}