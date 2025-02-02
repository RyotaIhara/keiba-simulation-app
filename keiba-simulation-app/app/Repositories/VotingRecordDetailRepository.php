<?php

namespace App\Repositories;

use Doctrine\ORM\EntityRepository;

class VotingRecordDetailRepository extends EntityRepository
{
    /** 対象日付から投票記録のデータを取得する **/
    public function getVotingRecordDetailByFromToDate($fromRaceDate, $toRaceDate, $otherWhereParams) {
        $queryBuilder = $this->createQueryBuilder('vrd')
            ->addSelect('vr')
            ->addSelect('ri')
            ->innerJoin('vrd.votingRecord', 'vr')
            ->innerJoin('vr.raceInfo', 'ri')
            ->where('ri.raceDate BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $fromRaceDate)
            ->setParameter('endDate', $toRaceDate);
        
        if (isset($otherWhereParams['hitStatus']) && !is_null($otherWhereParams['hitStatus']))
        {
            $queryBuilder
                ->andWhere('vrd.hitStatus = :hitStatus')
                ->setParameter('hitStatus', $otherWhereParams['hitStatus']);
        }
        if (isset($otherWhereParams['jyoCd']) && !is_null($otherWhereParams['jyoCd']))
        {
            $queryBuilder
                ->andWhere('ri.jyoCd = :jyoCd')
                ->setParameter('jyoCd', $otherWhereParams['jyoCd']);
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
