<?php

namespace App\Repositories;

use Doctrine\ORM\EntityRepository;

class RaceScheduleRepository extends EntityRepository
{
    // ここにカスタムメソッドを追加できます

    /** 日付を指定して、レーススケジュールを取得するメソッド **/
    public function getRaceSchedulesByDate($fromRaceDate, $toRaceDate) {
        return $this->createQueryBuilder('r')
        ->where('r.raceDate BETWEEN :startDate AND :endDate')
        ->setParameter('startDate', $fromRaceDate)
        ->setParameter('endDate', $toRaceDate)
        ->getQuery()
        ->getResult();
    }
}
