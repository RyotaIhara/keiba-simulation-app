<?php

namespace App\Services\Scraping;

use App\Services\Scraping\ScrapingBase;
use App\Services\Crud\VotingRecordDetailService;
use App\Services\Crud\RaceRefundAmountService;

class BatchUpdateVotingRefundAmountService extends ScrapingBase
{
    /** 対象日付から投票記録のデータを取得する **/
    public function getVotingRecordDetailByFromToDate($fromRaceDate, $toRaceDate, $otherWhereParams) {
        $votingRecordDetailService = app(VotingRecordDetailService::class);
        return $votingRecordDetailService->getVotingRecordDetailByFromToDate($fromRaceDate, $toRaceDate, $otherWhereParams);
    }

    /** 投票記録の払戻金データを更新する **/
    public function updateVotingRefundAmount($votingRecordDetail) {
        try {
            $votingRecordDetailService = app(VotingRecordDetailService::class);
            $raceRefundAmountService = app(RaceRefundAmountService::class);

            $votingRecord = $votingRecordDetail->getVotingRecord();

            // race_refund_amountのデータを取得する
            $whereParams = [
                'raceInfo' => $votingRecord->getRaceInfo(),
                'bettingTypeMst' => $votingRecord->getBettingTypeMst()
            ];
            $raceRefundAmountDatas = $raceRefundAmountService->getRaceRefundAmountDatas($whereParams);

            $votingUmaBanData = explode(',' ,$votingRecordDetail->getVotingUmaBan());

            // 更新用のパラメータ
            $paramsForUpdate = array(
                'votingRecord' => $votingRecord,
                'votingUmaBan' => $votingRecordDetail->getVotingUmaBan(),
                'votingAmount' => $votingRecordDetail->getVotingAmount(),
                'refundAmount' => $votingRecordDetail->getRefundAmount(),
                'hitStatus' => 2, // 外れ
            );

            foreach ($raceRefundAmountDatas as $raceRefundAmount) {
                $resultUmaBanDatas = explode(',' ,$raceRefundAmount->getResultUmaBan());

                // 買い目の順番は重要ではない場合は配列の中身をソートする
                if (!$votingRecord->getBettingTypeMst()->getIsOrdered()) {
                    sort($resultUmaBanDatas);
                    sort($votingUmaBanData);
                }

                if ($resultUmaBanDatas === $votingUmaBanData) {
                    $updateRefundAmount = ($raceRefundAmount->getRefundAmount() * $votingRecordDetail->getVotingAmount()) / 100;
                    $paramsForUpdate['hitStatus'] = 1; // 的中
                    $paramsForUpdate['refundAmount'] = $updateRefundAmount;
                    echo "voting_record：" . $votingRecordDetail->getId() . "の払戻金データを更新します\n";
                    break;
                }
            }

            if (!empty($raceRefundAmountDatas)) {
                $votingRecordDetailService->updateVotingRecordDetail($votingRecordDetail->getId(), $paramsForUpdate);
            }
        } catch (\Exception $e) {
            echo "voting_record：" . $votingRecord->getId() . "のデータで、updateVotingRefundAmountメソッドの実施に失敗しました\n";
        }
    }
}