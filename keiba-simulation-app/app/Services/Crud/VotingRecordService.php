<?php

namespace App\Services\Crud;

use Doctrine\ORM\EntityManagerInterface;
use App\Entities\VotingRecord;
use App\Services\Crud\CrudBase;
use App\Services\Crud\RaceInfoService;
use App\Services\Crud\HowToBuyMstService;

class VotingRecordService extends CrudBase
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
    }

    /** voting_recordテーブルのデータ一覧を取得する **/
    public function getAllVotingRecords()
    {
        return $this->entityManager->getRepository(VotingRecord::class)->getAllRacecourseMsts();
    }

    /** IDからvoting_recordテーブルのデータを取得する **/
    public function getVotingRecord($id)
    {
        return $this->entityManager->getRepository(VotingRecord::class)->find($id);
    }

    /** 対象日付から投票記録のデータを取得する **/
    public function getVotingRecordByFromToDate($fromRaceDate, $toRaceDate, $otherWhereParams) {
        return $this->entityManager->getRepository(VotingRecord::class)->getVotingRecordByFromToDate($fromRaceDate, $toRaceDate, $otherWhereParams);
    }

    /** voting_recordにデータを作成する **/
    public function createVotingRecord(array $data)
    {
        $votingRecord = new VotingRecord();

        $votingRecord = $this->setVotingRecord($votingRecord, $data);

        $this->entityManager->persist($votingRecord);
        $this->entityManager->flush();

    }

    /** voting_recordのデータを更新する **/
    public function updateVotingRecord($id, array $data)
    {
        $votingRecordRepository = $this->entityManager->getRepository(VotingRecord::class);
        $votingRecord = $votingRecordRepository->find($id);

        $votingRecord = $this->setVotingRecord($votingRecord, $data);

        $this->entityManager->persist($votingRecord);
        $this->entityManager->flush();
    }

    /** voting_recordのデータを物理削除する **/
    public function deleteVotingRecord($id)
    {
        $votingRecordRepository = $this->entityManager->getRepository(VotingRecord::class);
        $votingRecord = $votingRecordRepository->find($id);

        if (!empty($votingRecord)) {
            $this->entityManager->remove($votingRecord);
            $this->entityManager->flush();
        }
    }

    /** createやupdate用にデータをセットする **/
    private function setVotingRecord($votingRecord, $data)
    {
        $raceInfoService = app(RaceInfoService::class);
        $raceInfo = $raceInfoService->getRaceInfo($this->getValue($data, 'race_info_id', 'raceInfoId'));

        $howToBuyMstService = app(HowToBuyMstService::class);
        $howToBuyMst = $howToBuyMstService->getHowToBuyMst($this->getValue($data, 'how_to_buy_mst_id', 'howToBuyMstId'));

        $votingRecord->setRaceInfo($raceInfo);
        $votingRecord->setHowToBuyMst($howToBuyMst);
        $votingRecord->setVotingUmaBan($this->getValue($data, 'voting_uma_ban', 'votingUmaBan'));
        $votingRecord->setVotingAmount($this->getValue($data, 'voting_amount', 'votingAmount'));
        $votingRecord->setRefundAmount($this->getValue($data, 'refund_amount', 'refundAmount'));
        if (!empty($this->getValue($data, 'hit_status', 'hitStatus'))) {
            $votingRecord->setHitStatus($this->getValue($data, 'hit_status', 'hitStatus'));
        }
        if (!empty($this->getValue($data, 'created_at', 'createdAt'))) {
            $votingRecord->setCreatedAt($this->getValue($data, 'created_at', 'createdAt'));
        }
        if (!empty($this->getValue($data, 'updated_at', 'updatedAt'))) {
            $votingRecord->setUpdatedAt($this->getValue($data, 'updated_at', 'updatedAt'));
        }

        return $votingRecord;
    }

}