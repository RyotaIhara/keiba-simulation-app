<?php

namespace App\Services\Crud;

use Doctrine\ORM\EntityManagerInterface;
use App\Entities\VotingRecord;
use App\Services\Crud\CrudBase;

class VotingRecordService extends CrudBase
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
    }

    /** voting_recordテーブルのデータ一覧を取得する **/
    public function getAllVotingRecords()
    {
        return $this->entityManager->getRepository(VotingRecord::class)->findAll();
    }

    /** IDからvoting_recordテーブルのデータを取得する **/
    public function getVotingRecord($id)
    {
        return $this->entityManager->getRepository(VotingRecord::class)->find($id);
    }

    /** ユニークなカラムを指定してデータを1つ取得する **/
    public function getVotingRecordByUniqueColumn($whereParams) {
        return $this->entityManager->getRepository(VotingRecord::class)->findOneBy($whereParams);
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
        $votingRecord->setRaceInfoId($this->getValue($data, 'race_info_id', 'raceInfoId'));
        $votingRecord->setHowToBuyMstId($this->getValue($data, 'how_to_buy_mst_id', 'howToBuyMstId'));
        $votingRecord->setVotingUmaBan($this->getValue($data, 'voting_uma_ban ', 'votingUmaBan '));
        $votingRecord->setVotingAmount($this->getValue($data, 'voting_amount', 'votingAmount'));
        $votingRecord->setRefundAmount($this->getValue($data, 'refund_amount', 'refundAmount'));

        return $votingRecord;
    }

}