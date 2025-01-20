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

    public function getRaceSchedulesWithCourseMst($whereParams): array
    {
        $sql = "
            SELECT 
                rs.*, rcm.id AS racecourse_mst_id, rcm.racecourse_name
            FROM race_schedule rs
            INNER JOIN racecourse_mst rcm ON rcm.jyo_cd = rs.jyo_cd
            WHERE rs.race_date = :race_date
        ";

        $connection = $this->getEntityManager()->getConnection();
        $result = $connection->executeQuery($sql, ['race_date' => $whereParams['race_date']]);

        return $result->fetchAllAssociative();
    }

}
