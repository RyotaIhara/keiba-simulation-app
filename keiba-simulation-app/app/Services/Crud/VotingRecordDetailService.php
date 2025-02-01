<?php

namespace App\Services\Crud;

use App\Entities\VotingRecordDetail;
use App\Services\Crud\CrudBase;

class VotingRecordDetailService extends CrudBase
{
    /** voting_record_detailテーブルのデータ一覧を取得する **/
    public function getAllVotingRecordDetails()
    {
        return $this->entityManager->getRepository(VotingRecordDetail::class)->findAll();
    }

    /** IDからvoting_record_detailテーブルのデータを取得する **/
    public function getVotingRecordDetail($id)
    {
        return $this->entityManager->getRepository(VotingRecordDetail::class)->find($id);
    }

    /** ユニークなカラムを指定してデータを1つ取得する **/
    public function getVotingRecordDetailByUniqueColumn($whereParams) {
        return $this->entityManager->getRepository(VotingRecordDetail::class)->findOneBy($whereParams);
    }

    /** 対象日付から投票記録のデータを取得する **/
    public function getVotingRecordDetailByFromToDate($fromRaceDate, $toRaceDate, $otherWhereParams) {
        return $this->entityManager->getRepository(VotingRecordDetail::class)->getVotingRecordDetailByFromToDate($fromRaceDate, $toRaceDate, $otherWhereParams);
    }

    /** voting_record_detailにデータを作成する **/
    public function createVotingRecordDetail(array $data)
    {
        $votingRecordDetail = new VotingRecordDetail();

        $updateFlag = False;
        $votingRecordDetail = $this->setVotingRecordDetail($votingRecordDetail, $data, $updateFlag);

        $this->entityManager->persist($votingRecordDetail);
        $this->entityManager->flush();

        return $votingRecordDetail;
    }

    /** voting_record_detailのデータを更新する **/
    public function updateVotingRecordDetail($id, array $data)
    {
        $votingRecordDetailRepository = $this->entityManager->getRepository(VotingRecordDetail::class);
        $votingRecordDetail = $votingRecordDetailRepository->find($id);

        $updateFlag = True;
        $votingRecordDetail = $this->setVotingRecordDetail($votingRecordDetail, $data, $updateFlag);

        $this->entityManager->persist($votingRecordDetail);
        $this->entityManager->flush();

        return $votingRecordDetail;
    }

    /** voting_record_detailのデータを削除する **/
    public function deleteVotingRecordDetail($id)
    {
        $votingRecordDetailRepository = $this->entityManager->getRepository(VotingRecordDetail::class);
        $votingRecordDetail = $votingRecordDetailRepository->find($id);

        if (!empty($votingRecordDetail)) {
            $this->entityManager->remove($votingRecordDetail);
            $this->entityManager->flush();
        }
    }

    /** createやupdate用にデータをセットする **/
    private function setVotingRecordDetail($votingRecordDetail, $data, $updateFlag)
    {
        $votingRecordDetail->setVotingRecord($this->getValue($data, 'voting_record', 'votingRecord'));
        $votingRecordDetail->setVotingUmaBan($this->getValue($data, 'voting_uma_ban', 'votingUmaBan'));
        $votingRecordDetail->setVotingAmount($this->getValue($data, 'voting_amount', 'votingAmount'));

        if (!is_null($this->getValue($data, 'refund_amount', 'refundAmount'))) {
            $votingRecordDetail->setRefundAmount($this->getValue($data, 'refund_amount', 'refundAmount'));
        }
        if (!is_null($this->getValue($data, 'hit_status', 'hitStatus'))) {
            $votingRecordDetail->setHitStatus($this->getValue($data, 'hit_status', 'hitStatus'));
        }

        if (!$updateFlag) {
            $votingRecordDetail->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
        }
        $votingRecordDetail->setUpdatedAt(new \DateTime(date('Y-m-d H:i:s')));

        return $votingRecordDetail;
    }

}