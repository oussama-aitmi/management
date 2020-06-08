<?php
namespace App\Service;


use App\Traits\ApiResponseTrait;

abstract class AbstractService
{
    /**
     * Using Service trait Response methods
     */
    use ApiResponseTrait;
}
