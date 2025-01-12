<?php

namespace App\Services;

class Base
{
    public static $NETKEIBA_LOCAL_RACE_DOMAIN_URL; // 地方競馬のURL

    public function __construct()
    {
        self::$NETKEIBA_LOCAL_RACE_DOMAIN_URL = config('config.NETKEIBA_LOCAL_RACE_DOMAIN_URL');
    }

}