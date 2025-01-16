<?php

namespace App\Repositories;

use Doctrine\ORM\EntityRepository;

class VotingRecordRepository extends EntityRepository
{
    // ここにカスタムメソッドを追加できます
    public function getAllRacecourseMsts() {
        return $this->createQueryBuilder('r')
        ->innerJoin('r.raceInfo', 'rci')
        ->addSelect('rci')
        //->innerJoin('App\Entities\RacecourseMst', 'rcm', 'WITH', 'rci.jyoCd = rcm.jyoCd')
        //->addSelect('rcm')
        //->where('r.votingAmount > :votingAmount')
        //->setParameter('votingAmount', 500)
        ->getQuery()
        ->getResult();
    }
}
