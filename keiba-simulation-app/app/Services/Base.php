<?php

namespace App\Services;

class Base
{
    protected static $NETKEIBA_LOCAL_RACE_DOMAIN_URL; // 地方競馬のURL
    protected static $LOGIN_URL;
    protected static $LOGIN_BASE_URL;

    public function __construct()
    {
        self::$NETKEIBA_LOCAL_RACE_DOMAIN_URL = config('config.NETKEIBA_LOCAL_RACE_DOMAIN_URL');
        self::$LOGIN_URL = config('config.LOGIN_URL');
        self::$LOGIN_BASE_URL = config('config.LOGIN_BASE_URL');
    }
}