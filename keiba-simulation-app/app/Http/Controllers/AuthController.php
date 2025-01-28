<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App\Services\Crud\UserService;
use App\Services\Crud\LoginSessionService;
use App\Services\General\AuthGeneral;
use DateTime;

class AuthController extends Controller
{
    private $authGeneral;
    private $loginSessionService;
    private $userService;

    public function __construct()
    {
        $this->authGeneral = app(AuthGeneral::class);
        $this->loginSessionService = app(LoginSessionService::class);
        $this->userService = app(UserService::class);
    }

    // ログインフォームの表示
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // ログイン処理
    public function login(Request $request)
    {
        $whereParams = [
            'code' => $request->input('code'),
            'password' => $request->input('password'),
        ];
        $user = $this->userService->getUserByUniqueColumn($whereParams);
        if (empty($user)) {
            // ログイン画面に戻す
            return redirect()->route('login')->withErrors(['message' => 'ユーザーコードまたはパスワードが間違っています']);
        }

        $this->authGeneral->execLogin($user);

        return redirect()->route('voting_record.index')->with('success', 'ログインに成功しました');
    }

    // ログアウト処理
    public function logout()
    {
        $authToken = Session::get('auth_token'); 
        Session::forget('auth_token');
        Session::forget('sessionUserName');

        // テーブルのexpire_timeも更新
        $loginSession = $this->loginSessionService->getLoginSessionByToken($authToken);
        if (!empty($loginSession)) {
            $this->loginSessionService->updateExpireTime($loginSession, new DateTime(date('Y-m-d H:i:s')));
        }

        return redirect()->route('login')->with('success', 'ログアウトしました');
    }
}
