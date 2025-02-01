<?php

namespace App\Services\Crud;

use App\Entities\BoxVotingRecord;
use App\Services\Crud\CrudBase;
use App\Services\General\AuthGeneral;

class BoxVotingRecordService extends CrudBase
{
    /** box_voting_recordテーブルのデータ一覧を取得する **/
    public function getAllBoxVotingRecords()
    {
        return $this->entityManager->getRepository(BoxVotingRecord::class)->findAll();
    }

    /** IDからbox_voting_recordテーブルのデータを取得する **/
    public function getBoxVotingRecord($id)
    {
        return $this->entityManager->getRepository(BoxVotingRecord::class)->find($id);
    }

    /** ユニークなカラムを指定してデータを1つ取得する **/
    public function getBoxVotingRecordByUniqueColumn($whereParams) {
        return $this->entityManager->getRepository(BoxVotingRecord::class)->findOneBy($whereParams);
    }

    /** box_voting_recordにデータを作成する **/
    public function createBoxVotingRecord(array $data)
    {
        $boxVotingRecord = new BoxVotingRecord();

        $updateFlag = False;
        $boxVotingRecord = $this->setBoxVotingRecord($boxVotingRecord, $data, $updateFlag);

        $this->entityManager->persist($boxVotingRecord);
        $this->entityManager->flush();

        return $boxVotingRecord;
    }

    /** box_voting_recordのデータを更新する **/
    public function updateBoxVotingRecord($id, array $data)
    {
        $boxVotingRecordRepository = $this->entityManager->getRepository(BoxVotingRecord::class);
        $boxVotingRecord = $boxVotingRecordRepository->find($id);

        $updateFlag = True;
        $boxVotingRecord = $this->setBoxVotingRecord($boxVotingRecord, $data, $updateFlag);

        $this->entityManager->persist($boxVotingRecord);
        $this->entityManager->flush();
    }

    /** box_voting_recordのデータを削除する **/
    public function deleteBoxVotingRecord($id)
    {
        $boxVotingRecordRepository = $this->entityManager->getRepository(BoxVotingRecord::class);
        $boxVotingRecord = $boxVotingRecordRepository->find($id);

        if (!empty($boxVotingRecord)) {
            $this->entityManager->remove($boxVotingRecord);
            $this->entityManager->flush();
        }
    }

    /** createやupdate用にデータをセットする **/
    private function setBoxVotingRecord($boxVotingRecord, $data, $updateFlag)
    {
        $boxVotingRecord->setVotingRecord($this->getValue($data, 'voting_record', 'votingRecord'));
        $boxVotingRecord->setVotingUmaBanBox($this->getValue($data, 'voting_uma_ban_box', 'votingUmaBanBox'));
        $boxVotingRecord->setVotingAmountBox($this->getValue($data, 'voting_amount_box', 'votingAmountBox'));

        if (!$updateFlag) {
            $boxVotingRecord->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
        }
        $boxVotingRecord->setUpdatedAt(new \DateTime(date('Y-m-d H:i:s')));

        return $boxVotingRecord;
    }

}