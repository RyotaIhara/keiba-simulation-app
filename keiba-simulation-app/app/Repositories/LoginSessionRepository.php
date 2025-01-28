<?php

namespace App\Repositories;

use Doctrine\ORM\EntityRepository;

class LoginSessionRepository extends EntityRepository
{
    /** tokenからデータを1つ取得する **/
    public function getLoginSessionByToken($token) {
        $queryBuilder = $this->createQueryBuilder('ls')
            ->addSelect('u')
            ->innerJoin('ls.user', 'u')
            ->where('ls.token = :token')
            ->andWhere('ls.expireTime > :now')
            ->setParameter('token', $token)
            ->setParameter('now', new \DateTime());

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}
