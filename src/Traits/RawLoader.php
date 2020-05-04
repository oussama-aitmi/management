<?php

namespace App\Traits;

trait RawLoader
{
    /**
     * @return array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }
}
