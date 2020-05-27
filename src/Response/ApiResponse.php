<?php

namespace App\Response;

use Symfony\Component\HttpFoundation\Response;

class ApiResponse
{
    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var string
     */
    private $title;

    /**
     * @var array
     */
    private $extraData = [];

    /**
     * @var string
     */
    private $state;

    /**
     * @param int $statusCode
     * @param string $title
     */
    public function __construct(int $statusCode, string $title = null)
    {
        $this->statusCode = $statusCode;
        $this->title =$title;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $error = [
            'status' => $this->statusCode,
            'title' => $this->title
        ];

        if(is_string($this->extraData['response'])){
            return $error['errors'] = array_merge(
                $error,
                ['message' =>$this->extraData['response']]
            );
        }

         return $error['errors'] = array_merge(
            $error,
            $this->extraData['response']
        );
    }

    /**
     * @param string $name
     */
    public function get(string $name)
    {
        return isset($this->extraData[$name])? $this->extraData[$name] : null;
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function set(string $name, $value)
    {
        $this->extraData[$name] = $value;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @return array
     */
    public function getExtraData(): array
    {
        return $this->extraData;
    }
}
