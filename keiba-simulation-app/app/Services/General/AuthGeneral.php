<?php

namespace App\Services\General;

use Illuminate\Support\Facades\Session;
use App\Services\General\GeneralBase;
use App\Services\Crud\LoginSessionService;
use Illuminate\Support\Str;
use DateTime;

class AuthGeneral extends GeneralBase
{
    private $loginSessionService;

    public function __construct()
    {
        $this->loginSessionService = app(LoginSessionService::class);
    }

    /** ログイン処理を実施する **/
    public function execLogin($user) {
        // トークン生成
        $token = hash('sha256', Str::random(60) . $user->getCode());

        // トークンを保存
        $paramsForInsert = array(
            'user' => $user,
            'token' => $token,
            'expireTime' => (new DateTime())->modify('+1 day'),
            'createdAt' => new DateTime(date('Y-m-d H:i:s')),
        );
        $this->loginSessionService->createLoingSession($paramsForInsert);

        // セッションにトークンを保存
        Session::put('auth_token', $token);
        Session::put('sessionUserName', $user->getUserName());

        $token = Session::get('auth_token');
    }

    /** ログインしているかどうかを判定 **/
    public function isLogin() {
        $authToken = Session::get('auth_token'); 

        $loginSession = $this->loginSessionService->getLoginSessionByToken($authToken);

        if (!empty($loginSession)) {
            return True;
        };

        return False;
    }

    /** ログインしているユーザー名を取得 **/
    public function getSessionUserName() {
        if (!empty(Session::get('sessionUserName'))) {
            return Session::get('sessionUserName');
        }

        $authToken = Session::get('auth_token');
        $loginSession = $this->loginSessionService->getLoginSessionByToken($authToken);

        if (!empty($loginSession->getUser()->getUserName())) {
            return $loginSession->getuser()->getUserName();
        };

        return "";
    }
}