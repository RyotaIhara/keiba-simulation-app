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

        $nagashiVotingRecord = $this->setNagashiVotingRecord($nagashiVotingRecord, $data);

        $this->entityManager->persist($nagashiVotingRecord);
        $this->entityManager->flush();

        return $nagashiVotingRecord;
    }

    /** nagashi_voting_recordのデータを更新する **/
    public function updateNagashiVotingRecord($id, array $data)
    {
        $nagashiVotingRecordRepository = $this->entityManager->getRepository(NagashiVotingRecord::class);
        $nagashiVotingRecord = $nagashiVotingRecordRepository->find($id);

        $nagashiVotingRecord = $this->setNagashiVotingRecord($nagashiVotingRecord, $data);

        $this->entityManager->persist($nagashiVotingRecord);
        $this->entityManager->flush();
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
    private function setNagashiVotingRecord($nagashiVotingRecord, $data)
    {
        $authGeneral = app(AuthGeneral::class);

        $nagashiVotingRecord->setUser($authGeneral->getLoginUser());
        $nagashiVotingRecord->setRaceInfo($this->getValue($data, 'race_info', 'raceInfo'));
        $nagashiVotingRecord->setHowToBuyMst($this->getValue($data, 'how_to_buy_mst', 'howToBuyMst'));
        $nagashiVotingRecord->setShaftPattern($data['howToNagashi']);
        $nagashiVotingRecord->setShaft($data['shaft']);
        $nagashiVotingRecord->setPartner($data['partner']);
        $nagashiVotingRecord->setVotingAmountNagashi($this->getValue($data, 'voting_amount_nagashi', 'votingAmountNagashi'));
        if (!empty($this->getValue($data, 'created_at', 'createdAt'))) {
            $nagashiVotingRecord->setCreatedAt($this->getValue($data, 'created_at', 'createdAt'));
        }
        if (!empty($this->getValue($data, 'updated_at', 'updatedAt'))) {
            $nagashiVotingRecord->setUpdatedAt($this->getValue($data, 'updated_at', 'updatedAt'));
        }

        return $nagashiVotingRecord;
    }

}