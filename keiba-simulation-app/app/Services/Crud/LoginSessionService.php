<?php

namespace App\Services\Crud;

use App\Entities\LoginSession;
use App\Services\Crud\CrudBase;

class LoginSessionService extends CrudBase
{
    /** tokenからデータを1つ取得する **/
    public function getLoginSessionByToken($token) {
        return $this->entityManager->getRepository(LoginSession::class)->getLoginSessionByToken([
            'token' => $token
        ]);
    }

    /** login_sessionにデータを作成する **/
    public function createLoingSession(array $data)
    {
        $loginSession = new LoginSession();

        $loginSession = $this->setLoginSession($loginSession, $data);

        $this->entityManager->persist($loginSession);
        $this->entityManager->flush();

    }

    /** login_sessionのexpire_timeを更新する **/
    public function updateExpireTime($loginSession, $expireTime)
    {
        $loginSession->setExpireTime($expireTime);

        $this->entityManager->persist($loginSession);
        $this->entityManager->flush();
    }

    /** create用にデータをセットする **/
    private function setLoginSession($loginSession, $data)
    {
        $loginSession->setUser($data['user']);
        $loginSession->setToken($data['token']);
        $loginSession->setExpireTime($this->getValue($data, 'expire_time', 'expireTime'));
        $loginSession->setCreatedAt($this->getValue($data, 'created_at', 'createdAt'));

        return $loginSession;
    }

}