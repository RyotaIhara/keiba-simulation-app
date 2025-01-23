<?php

namespace App\Repositories;

use Doctrine\ORM\EntityRepository;

class VotingRecordRepository extends EntityRepository
{
    const YET_HIT_STATUS = 0;

    // ここにカスタムメソッドを追加できます

    public function getAllRacecourseMsts() {
        /** race_infoのデータとともに、voting_recordのデータを取得する **/
        return $this->createQueryBuilder('r')
        ->innerJoin('r.raceInfo', 'rci')
        ->addSelect('rci')
        ->getQuery()
        ->getResult();
    }

    /** 対象日付から投票記録のデータを取得する **/
    public function getVotingRecordByFromToDate($fromRaceDate, $toRaceDate, $otherWhereParams) {
        $queryBuilder = $this->createQueryBuilder('vr')
            ->addSelect('ri')
            ->addSelect('htm')
            ->innerJoin('vr.raceInfo', 'ri')
            ->innerJoin('vr.howToBuyMst', 'htm')
            ->where('ri.raceDate BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $fromRaceDate)
            ->setParameter('endDate', $toRaceDate);
        
        if (isset($otherWhereParams['hitStatus']) && !is_null($otherWhereParams['hitStatus']))
        {
            $queryBuilder
                ->andWhere('vr.hitStatus = :hitStatus')
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
