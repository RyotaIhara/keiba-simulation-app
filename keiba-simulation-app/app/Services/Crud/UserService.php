<?php

namespace App\Services\Crud;

use App\Entities\User;
use App\Services\Crud\CrudBase;
//use Illuminate\Support\Facades\Hash;

class UserService extends CrudBase
{
    /** userテーブルのデータ一覧を取得する **/
    public function getAllUsers()
    {
        return $this->entityManager->getRepository(User::class)->findAll();
    }

    /** IDからuserテーブルのデータを取得する **/
    public function getUser($id)
    {
        return $this->entityManager->getRepository(User::class)->find($id);
    }

    /** ユニークなカラムを指定してデータを1つ取得する **/
    public function getUserByUniqueColumn($whereParams) {
        return $this->entityManager->getRepository(User::class)->findOneBy($whereParams);
    }

    /** userにデータを作成する **/
    public function createUser(array $data)
    {
        $user = new User();

        $user = $this->setUser($user, $data);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

    }

    /** userのデータを更新する **/
    public function updateUser($id, array $data)
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->find($id);

        $user = $this->setUser($user, $data);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /** userのデータを削除する **/
    public function deleteUser($id)
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->find($id);

        if (!empty($user)) {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
        }
    }

    /** createやupdate用にデータをセットする **/
    private function setUser($user, $data)
    {
        $user->setCode($data['code']);
        $user->setUsername($data['username']);
        $user->setPassword($data['password']);
        //$user->setPassword(Hash::make($data['password']));

        return $user;
    }

}