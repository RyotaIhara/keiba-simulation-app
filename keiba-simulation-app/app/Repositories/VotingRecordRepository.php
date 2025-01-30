<?php

namespace App\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

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

    /** 画面の一覧表示用にvoting_recordテーブルのデータを取得する **/
    public function getVotingRecordsIndexViewDatas($page, $pageSize, $whereParams) {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
            SELECT
                `voting_record`.`id` AS `id`,
                `voting_record`.`race_info_id` AS `race_info_id`,
                `voting_record`.`how_to_buy_mst_id` AS `how_to_buy_mst_id`,
                `voting_record`.`voting_uma_ban` AS `voting_uma_ban`,
                `voting_record`.`voting_amount` AS `voting_amount`,
                `voting_record`.`refund_amount` AS `refund_amount`,
                `voting_record`.`hit_status` AS `hit_status`,
                `racecourse_mst`.`racecourse_name` AS `racecourse_name`,
                `race_info`.`race_date` AS `race_date`,
                `race_info`.`race_num` AS `race_num`,
                `race_info`.`race_name` AS `race_name`,
                `race_info`.`entry_horce_count` AS `entry_horce_count`,
                `race_info`.`jyo_cd` AS `jyo_cd`,
                `how_to_buy_mst`.`how_to_buy_name` AS `how_to_buy_name`
            FROM 
                `voting_record`
            INNER JOIN `race_info` ON `race_info`.`id` = `voting_record`.`race_info_id`
            INNER JOIN `how_to_buy_mst` ON `how_to_buy_mst`.`id` = `voting_record`.`how_to_buy_mst_id`
            INNER JOIN `racecourse_mst` ON `racecourse_mst`.`jyo_cd` = `race_info`.`jyo_cd`
            WHERE 
                `race_info`.race_date = :raceDate
        ";

        if (isset($whereParams['hitStatus']) && !is_null($whereParams['hitStatus'])) {
            $sql .= " AND vr.hit_status = :hitStatus";
        }

        if (isset($whereParams['jyoCd']) && !is_null($whereParams['jyoCd'])) {
            $sql .= " AND ri.jyo_cd = :jyoCd";
        }

        $stmt = $conn->executeQuery($sql, $whereParams);
        $results = $stmt->fetchAllAssociative(); // 配列で取得

        return [
            'data' => $results,
            'totalItems' => count($results),
            'currentPage' => $page,
            'totalPages' => ceil(count($results) / $pageSize),
        ];
    }
}
