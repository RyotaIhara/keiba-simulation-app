<?php

namespace App\Services\Crud;

use App\Entities\NagashiVotingRecord;
use App\Services\Crud\CrudBase;
use App\Services\General\AuthGeneral;

class NagashiVotingRecordService extends CrudBase
{
    /** nagashi_voting_recordテーブルのデータ一覧を取得する **/
    public function getAllNagashiVotingRecords()
    {
        return $this->entityManager->getRepository(NagashiVotingRecord::class)->findAll();
    }

    /** IDからnagashi_voting_recordテーブルのデータを取得する **/
    public function getNagashiVotingRecord($id)
    {
        return $this->entityManager->getRepository(NagashiVotingRecord::class)->find($id);
    }

    /** ユニークなカラムを指定してデータを1つ取得する **/
    public function getNagashiVotingRecordByUniqueColumn($whereParams) {
        return $this->entityManager->getRepository(NagashiVotingRecord::class)->findOneBy($whereParams);
    }

    /** nagashi_voting_recordにデータを作成する **/
    public function createNagashiVotingRecord(array $data)
    {
        $nagashiVotingRecord = new NagashiVotingRecord();

        $updateFlag = False;
        $nagashiVotingRecord = $this->setNagashiVotingRecord($nagashiVotingRecord, $data, $updateFlag);

        $this->entityManager->persist($nagashiVotingRecord);
        $this->entityManager->flush();

        return $nagashiVotingRecord;
    }

    /** nagashi_voting_recordのデータを更新する **/
    public function updateNagashiVotingRecord($id, array $data)
    {
        $nagashiVotingRecordRepository = $this->entityManager->getRepository(NagashiVotingRecord::class);
        $nagashiVotingRecord = $nagashiVotingRecordRepository->find($id);

        $updateFlag = True;
        $nagashiVotingRecord = $this->setNagashiVotingRecord($nagashiVotingRecord, $data, $updateFlag);

        $this->entityManager->persist($nagashiVotingRecord);
        $this->entityManager->flush();

        return $nagashiVotingRecord;
    }

    /** nagashi_voting_recordのデータを削除する **/
    public function deleteNagashiVotingRecord($id)
    {
        $nagashiVotingRecordRepository = $this->entityManager->getRepository(NagashiVotingRecord::class);
        $nagashiVotingRecord = $nagashiVotingRecordRepository->find($id);

        if (!empty($nagashiVotingRecord)) {
            $this->entityManager->remove($nagashiVotingRecord);
            $this->entityManager->flush();
        }
    }

    /** createやupdate用にデータをセットする **/
    private function setNagashiVotingRecord($nagashiVotingRecord, $data, $updateFlag)
    {
        $nagashiVotingRecord->setVotingRecord($this->getValue($data, 'voting_record', 'votingRecord'));
        $nagashiVotingRecord->setShaftPattern($this->getValue($data, 'how_to_nagashi', 'howToNagashi'));
        $nagashiVotingRecord->setShaft($data['shaft']);
        $nagashiVotingRecord->setPartner($data['partner']);
        $nagashiVotingRecord->setVotingAmountNagashi($this->getValue($data, 'voting_amount_nagashi', 'votingAmountNagashi'));

        if (!$updateFlag) {
            $nagashiVotingRecord->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
        }
        $nagashiVotingRecord->setUpdatedAt(new \DateTime(date('Y-m-d H:i:s')));

        return $nagashiVotingRecord;
    }

}