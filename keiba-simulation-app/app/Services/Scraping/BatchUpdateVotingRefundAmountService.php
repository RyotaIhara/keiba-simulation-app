<?php

namespace App\Services\Scraping;

use App\Services\Scraping\ScrapingBase;
use App\Services\Crud\VotingRecordService;
use App\Services\Crud\RaceRefundAmountService;
use DateTime;

class BatchUpdateVotingRefundAmountService extends ScrapingBase
{
    /** 対象日付から投票記録のデータを取得する **/
    public function getVotingRecordByFromToDate($fromRaceDate, $toRaceDate) {
        $votingRecordService = app(VotingRecordService::class);
        return $votingRecordService->getVotingRecordByFromToDate($fromRaceDate, $toRaceDate);
    }

    /** 投票記録の払戻金データを更新する **/
    public function updateVotingRefundAmount($votingRecord) {
        try {
            $votingRecordService = app(VotingRecordService::class);
            $raceRefundAmountService = app(RaceRefundAmountService::class);

            // race_refund_amountからデータを取得する
            $whereParams = [
                'raceInfo' => $votingRecord->getRaceInfo(),
                'howToBuyMst' => $votingRecord->getHowToBuyMst()
            ];
            $raceRefundAmountDatas = $raceRefundAmountService->getRaceRefundAmountDatas($whereParams);

            $votingUmaBanData = explode(',' ,$votingRecord->getVotingUmaBan());

            // 更新用のパラメータ
            $paramsForUpdate = array(
                'race_info_id' => $votingRecord->getRaceInfo()->getId(),
                'how_to_buy_mst_id' => $votingRecord->getHowToBuyMst()->getId(),
                'voting_uma_ban' => $votingRecord->getVotingUmaBan(),
                'voting_amount' => $votingRecord->getVotingAmount(),
                'refund_amount' => $votingRecord->getRefundAmount(),
                'hit_status' => 2,
                'updated_at' => new DateTime(date('Y-m-d H:i:s'))
            );

            foreach ($raceRefundAmountDatas as $raceRefundAmount) {
                $resultUmaBanDatas = explode(',' ,$raceRefundAmount->getResultUmaBan());

                // 買い目の順番は重要ではない場合は配列の中身をソートする
                if (!$votingRecord->getHowToBuyMst()->getIsOrdered()) {
                    sort($resultUmaBanDatas);
                    sort($votingUmaBanData);
                }

                if ($resultUmaBanDatas === $votingUmaBanData) {
                    $updateRefundAmount = ($raceRefundAmount->getRefundAmount() * $votingRecord->getVotingAmount()) / 100;
                    $paramsForUpdate['hit_status'] = 1;
                    $paramsForUpdate['refund_amount'] = $updateRefundAmount;
                    echo "voting_record：" . $votingRecord->getId() . "の払戻金データを更新します\n";
                    break;
                }
            }

            if (!empty($raceRefundAmountDatas)) {
                $votingRecordService->updateVotingRecord($votingRecord->getId(), $paramsForUpdate);
            }
        } catch (\Exception $e) {
            echo "voting_record：" . $votingRecord->getId() . "のデータで、updateVotingRefundAmountメソッドの実施に失敗しました\n";
        }
    }
}