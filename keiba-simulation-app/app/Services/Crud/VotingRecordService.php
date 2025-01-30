<?php

namespace App\Services\Crud;

use Doctrine\ORM\EntityManagerInterface;
use App\Entities\VotingRecord;
use App\Services\Crud\CrudBase;
use App\Services\General\AuthGeneral;

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

    /** 画面の一覧表示用にvoting_recordテーブルのデータを取得する **/
    public function getVotingRecordsIndexViewDatas($page, $pageSize, $searchForm)
    {
        $whereParams = [
            'raceDate' => $searchForm['raceDate'],
            'raceNum' => $searchForm['raceNum'],
            'jyoCd' => $searchForm['racecourse']
        ];
        return $this->entityManager->getRepository(VotingRecord::class)->getVotingRecordsIndexViewDatas($page, $pageSize, $whereParams);
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
        $authGeneral = app(AuthGeneral::class);

        $votingRecord->setUser($authGeneral->getLoginUser());
        $votingRecord->setRaceInfo($this->getValue($data, 'race_info', 'raceInfo'));
        $votingRecord->setHowToBuyMst($this->getValue($data, 'how_to_buy_mst', 'howToBuyMst'));
        $votingRecord->setVotingUmaBan($this->getValue($data, 'voting_uma_ban', 'votingUmaBan'));
        $votingRecord->setVotingAmount($this->getValue($data, 'voting_amount', 'votingAmount'));
        $votingRecord->setRefundAmount($this->getValue($data, 'refund_amount', 'refundAmount'));
        if (!empty($this->getValue($data, 'hit_status', 'hitStatus'))) {
            $votingRecord->setHitStatus($this->getValue($data, 'hit_status', 'hitStatus'));
        }

        // フォーメーション・ボックス・ながしの場合
        if (!empty($this->getValue($data, 'formation_voting_record', 'formationVotingRecord'))) {
            $votingRecord->setFormationVotingRecord($this->getValue($data, 'formation_voting_record', 'formationVotingRecord'));
        }
        if (!empty($this->getValue($data, 'box_voting_record', 'boxVotingRecord'))) {
            $votingRecord->setBoxVotingRecord($this->getValue($data, 'box_voting_record', 'boxVotingRecord'));
        }
        if (!empty($this->getValue($data, 'nagashi_voting_record', 'nagashiVotingRecord'))) {
            $votingRecord->setNagashiVotingRecord($this->getValue($data, 'nagashi_voting_record', 'nagashiVotingRecord'));
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