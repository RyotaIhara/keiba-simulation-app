<?php

namespace App\Http\Controllers;
use App\Services\General\AuthGeneral;

abstract class Controller
{
    protected function isLogin() {
        $authGeneral = new AuthGeneral();
        if(!$authGeneral->isLogin()) {
            return False;
        }

        return True;
    }
}

