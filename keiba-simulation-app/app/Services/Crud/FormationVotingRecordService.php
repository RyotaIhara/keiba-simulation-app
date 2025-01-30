<?php

namespace App\Services\Crud;

use App\Entities\FormationVotingRecord;
use App\Services\Crud\CrudBase;
use App\Services\General\AuthGeneral;

class FormationVotingRecordService extends CrudBase
{
    /** formation_voting_recordテーブルのデータ一覧を取得する **/
    public function getAllFormationVotingRecords()
    {
        return $this->entityManager->getRepository(FormationVotingRecord::class)->findAll();
    }

    /** IDからformation_voting_recordテーブルのデータを取得する **/
    public function getFormationVotingRecord($id)
    {
        return $this->entityManager->getRepository(FormationVotingRecord::class)->find($id);
    }

    /** ユニークなカラムを指定してデータを1つ取得する **/
    public function getFormationVotingRecordByUniqueColumn($whereParams) {
        return $this->entityManager->getRepository(FormationVotingRecord::class)->findOneBy($whereParams);
    }

    /** formation_voting_recordにデータを作成する **/
    public function createFormationVotingRecord(array $data)
    {
        $formationVotingRecord = new FormationVotingRecord();

        $formationVotingRecord = $this->setFormationVotingRecord($formationVotingRecord, $data);

        $this->entityManager->persist($formationVotingRecord);
        $this->entityManager->flush();

        return $formationVotingRecord;
    }

    /** formation_voting_recordのデータを更新する **/
    public function updateFormationVotingRecord($id, array $data)
    {
        $formationVotingRecordRepository = $this->entityManager->getRepository(FormationVotingRecord::class);
        $formationVotingRecord = $formationVotingRecordRepository->find($id);

        $formationVotingRecord = $this->setFormationVotingRecord($formationVotingRecord, $data);

        $this->entityManager->persist($formationVotingRecord);
        $this->entityManager->flush();
    }

    /** formation_voting_recordのデータを削除する **/
    public function deleteFormationVotingRecord($id)
    {
        $formationVotingRecordRepository = $this->entityManager->getRepository(FormationVotingRecord::class);
        $formationVotingRecord = $formationVotingRecordRepository->find($id);

        if (!empty($formationVotingRecord)) {
            $this->entityManager->remove($formationVotingRecord);
            $this->entityManager->flush();
        }
    }

    /** createやupdate用にデータをセットする **/
    private function setFormationVotingRecord($formationVotingRecord, $data)
    {
        $authGeneral = app(AuthGeneral::class);

        $formationVotingRecord->setUser($authGeneral->getLoginUser());
        $formationVotingRecord->setRaceInfo($this->getValue($data, 'race_info', 'raceInfo'));
        $formationVotingRecord->setHowToBuyMst($this->getValue($data, 'how_to_buy_mst', 'howToBuyMst'));
        $formationVotingRecord->setVotingUmaBan1($this->getValue($data, 'voting_uma_ban_1', 'votingUmaBan1'));
        $formationVotingRecord->setVotingUmaBan2($this->getValue($data, 'voting_uma_ban_2', 'votingUmaBan2'));
        $formationVotingRecord->setVotingUmaBan3($this->getValue($data, 'voting_uma_ban_3', 'votingUmaBan3'));
        $formationVotingRecord->setVotingAmountFormation($this->getValue($data, 'voting_amount_formaation', 'votingAmountFormation'));
        if (!empty($this->getValue($data, 'created_at', 'createdAt'))) {
            $formationVotingRecord->setCreatedAt($this->getValue($data, 'created_at', 'createdAt'));
        }
        if (!empty($this->getValue($data, 'updated_at', 'updatedAt'))) {
            $formationVotingRecord->setUpdatedAt($this->getValue($data, 'updated_at', 'updatedAt'));
        }

        return $formationVotingRecord;
    }

}