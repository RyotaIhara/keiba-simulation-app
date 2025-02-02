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

    /** 画面の一覧表示用にvoting_recordテーブルのデータを取得する **/
    public function getVotingRecordsIndexViewDatas($page, $pageSize, $whereParams) {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
            SELECT
                `voting_record`.`id` AS `id`,
                `voting_record`.`race_info_id` AS `race_info_id`,
                `voting_record`.`how_to_buy_mst_id` AS `how_to_buy_mst_id`,
                CASE 
                    WHEN how_to_buy_code = 'box' THEN `box_voting_record`.`voting_uma_ban_box`
                    WHEN how_to_buy_code = 'nagashi' THEN `nagashi_voting_record`.`shaft` + '⇒' + `nagashi_voting_record`.`partner`
                    WHEN how_to_buy_code = 'formation' THEN `formation_voting_record`.`voting_uma_ban_1` + '⇒' + `formation_voting_record`.`voting_uma_ban_2` + '⇒' + `formation_voting_record`.`voting_uma_ban_3`
                    ELSE `voting_record_detail`.`voting_uma_ban`
                END AS `voting_uma_ban`,
                SUM(`voting_record_detail`.`voting_amount`) AS `voting_amount`,
                SUM(`voting_record_detail`.`refund_amount`) AS `refund_amount`,
                hit_status_group.status_pattern AS `hit_status`,
                `racecourse_mst`.`racecourse_name` AS `racecourse_name`,
                `race_info`.`race_date` AS `race_date`,
                `race_info`.`race_num` AS `race_num`,
                `race_info`.`race_name` AS `race_name`,
                `race_info`.`entry_horce_count` AS `entry_horce_count`,
                `race_info`.`jyo_cd` AS `jyo_cd`,
                `how_to_buy_mst`.`how_to_buy_code` AS `how_to_buy_code`,
                `how_to_buy_mst`.`how_to_buy_name` AS `how_to_buy_name`,
                `betting_type_mst`.`betting_type_code` AS `betting_type_code`,
                `betting_type_mst`.`betting_type_name` AS `betting_type_name`
            FROM 
                `voting_record`
            INNER JOIN `voting_record_detail` ON `voting_record`.`id` = `voting_record_detail`.`voting_record_id`
            LEFT JOIN `box_voting_record` ON `voting_record`.`id` = `box_voting_record`.`voting_record_id`
            LEFT JOIN `nagashi_voting_record` ON `voting_record`.`id` = `nagashi_voting_record`.`voting_record_id`
            LEFT JOIN `formation_voting_record` ON `voting_record`.`id` = `formation_voting_record`.`voting_record_id`
            INNER JOIN `race_info` ON `race_info`.`id` = `voting_record`.`race_info_id`
            INNER JOIN `betting_type_mst` ON `betting_type_mst`.`id` = `voting_record`.`betting_type_mst_id`
            INNER JOIN `how_to_buy_mst` ON `how_to_buy_mst`.`id` = `voting_record`.`how_to_buy_mst_id`
            INNER JOIN `racecourse_mst` ON `racecourse_mst`.`jyo_cd` = `race_info`.`jyo_cd`
            INNER JOIN (
                SELECT 
                    voting_record_id,
                    GROUP_CONCAT(DISTINCT hit_status ORDER BY hit_status) AS status_list,
                    CASE 
                        WHEN GROUP_CONCAT(DISTINCT hit_status ORDER BY hit_status) = '0' THEN '0' -- すべて0
                        WHEN GROUP_CONCAT(DISTINCT hit_status ORDER BY hit_status) = '2' THEN '2' -- すべて2
                        WHEN GROUP_CONCAT(DISTINCT hit_status ORDER BY hit_status) LIKE '%1%' THEN '1' -- 1つでも1があるパターン
                        ELSE 'other' 
                    END AS status_pattern
                FROM voting_record_detail
                GROUP BY voting_record_id
            ) hit_status_group ON hit_status_group.voting_record_id = `voting_record`.`id`
            WHERE 
                `race_info`.race_date = :raceDate
        ";

        if (isset($whereParams['userId']) && !is_null($whereParams['userId'])) {
            $sql .= " AND voting_record.user_id = :userId";
        }

        if (isset($whereParams['hitStatus']) && !is_null($whereParams['hitStatus'])) {
            $sql .= " AND voting_record_detail.hit_status = :hitStatus";
        }

        if (isset($whereParams['jyoCd']) && !is_null($whereParams['jyoCd'])) {
            $sql .= " AND race_info.jyo_cd = :jyoCd";
        }

        $sql .= " GROUP BY `voting_record`.`id`, hit_status_group.status_pattern";
        $sql .= " ORDER BY `voting_record`.`id` DESC";

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
