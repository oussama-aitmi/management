<?php

namespace App\Rules;


abstract class AbstractRulesService
{

    /**
     * uid generator
     *
     * @param string $uid
     *
     * return string
     */
    public function uid(string $uid = null): string
    {
        if (empty($uid)) {
            if (function_exists('com_create_guid') === true) {
                return trim(com_create_guid(), '{}');
            }
            return sprintf(
                '%04X%04X-%04X-%04X-%04X-%04X%04X%04X',
                mt_rand(0, 65535),
                mt_rand(0, 65535),
                mt_rand(0, 65535),
                mt_rand(16384, 20479),
                mt_rand(32768, 49151),
                mt_rand(0, 65535),
                mt_rand(0, 65535),
                mt_rand(0, 65535)
            );
        }
        return $uid;
    }
}
