<?php

namespace App\Services\Scraping\Simulation;

use App\Services\Scraping\BatchRaceBaseService;
use App\Services\Crud\Simulation\NetkeibaExpectedHorseService;
use App\Services\Crud\RaceInfoService;
use App\Services\Crud\VotingRecordService;
use App\Services\Crud\VotingRecordDetailService;
use App\Services\Crud\HowToBuyMstService;
use App\Services\Crud\BettingTypeMstService;

class BatchSimulation01Service extends BatchRaceBaseService
{
     /** バッチのメイン処理をここに記載 **/
    public function mainExec($raceId, $otherParams) {
        try {
            $year = substr($raceId, 0, 4);
            $jyoCd = substr($raceId, 4, 2);
            $month = substr($raceId, 6, 2);
            $day = substr($raceId, 8, 2);
            $raceNum = substr($raceId, 10, 2);

            $raceInfoService = app(RaceInfoService::class);
            $netkeibaExpectedHorseService = app(NetkeibaExpectedHorseService::class);

            /** race_infoの情報を取得 **/
            $raceInfo = $raceInfoService->getRaceInfoByUniqueColumn([
                'raceDate' => new \DateTime($year . '-' . $month . '-' . $day),
                'jyoCd' => $jyoCd,
                'raceNum' => $raceNum,
            ]);

            /** race_cardの一覧を取得 **/
            $raceCardList = $raceInfo->getRaceCardList();

            /** netkeiba_expected_horseのデータを取得 **/
            $expectedHorseList = $netkeibaExpectedHorseService->getNetkeibaExpectedHorsesByRaceInfo($raceInfo);

            $this->votingSimulation01(
                $otherParams['userId'],
                $raceInfo, 
                $this->formatArrayKeyUmabanValueFavorite($raceCardList),
                $expectedHorseList,
            );
        } catch (\Exception $ex) {
            echo $ex->getMessage() . "\n";
        }
    }

    /**
     * 推奨馬下記のパターンに当てはまっていれば投票を行う
     *  
     * オッズが10.0以下⇒単勝30000円
     * オッズが10～20の間⇒単勝10000円＋複勝20000円
     * オッズが20以上⇒複勝10000円 
    **/
    private function votingSimulation01($userId, $raceInfo, $raceCardArray, $expectedHorseList) {
        try {
            // 推奨馬3頭をループして、条件に当てはまるかを確認
            foreach ($expectedHorseList as $expectedHorse) {
                $favourite = $raceCardArray[$expectedHorse->getUmaBan()];

                // オッズのデータかまだ入ってなければ何もしない
                if (!is_null($favourite)) {
                    if ($favourite <= 10) {
                        // オッズが10.0以下
                        $this->execVoting($userId, $raceInfo, $howToBuyCode = 'normal', $bettingTypeCode = 'Tansho', $expectedHorse->getUmaBan(), $votingAmount = 30000);
                    } else if (10 < $favourite && $favourite <= 20) {
                        // オッズが10～20の間
                        $this->execVoting($userId, $raceInfo, $howToBuyCode = 'normal', $bettingTypeCode = 'Tansho', $expectedHorse->getUmaBan(), $votingAmount = 10000);
                        $this->execVoting($userId, $raceInfo, $howToBuyCode = 'normal', $bettingTypeCode = 'Fukusho', $expectedHorse->getUmaBan(), $votingAmount = 20000);
                    }  else {
                        // オッズが20以上
                        $this->execVoting($userId, $raceInfo, $howToBuyCode = 'normal', $bettingTypeCode = 'Fukusho', $expectedHorse->getUmaBan(), $votingAmount = 210000);
                    }
                }
            }
        } catch (\Exception $ex) {
            echo $ex->getMessage() . "\n";
        }
    }

    /** 投票データの作成を行う **/
    private function execVoting($userId, $raceInfo, $howToBuyCode, $bettingTypeCode, $votingUmaBan, $votingAmount) {
        $votingRecordService = app(VotingRecordService::class);
        $votingRecordDetailService = app(VotingRecordDetailService::class);
        $howToBuyMstService = app(HowToBuyMstService::class);
        $bettingTypeMstService = app(BettingTypeMstService::class);

        $howToBuyMst = $howToBuyMstService->getHowToBuyMstByUniqueColumn([
            'howToBuyCode' => $howToBuyCode,
        ]);
        $bettingTypeMst = $bettingTypeMstService->getBettingTypeMstByUniqueColumn([
            'bettingTypeCode' => $bettingTypeCode,
        ]);

        $votingRecord = $votingRecordService->createVotingRecord([
            'userId' => $userId,
            'raceInfo' => $raceInfo,
            'howToBuyMst' => $howToBuyMst,
            'bettingTypeMst' =>  $bettingTypeMst,
        ]);
        $votingRecordDetailService->createVotingRecordDetail([
            'votingRecord' =>  $votingRecord ,
            'votingUmaBan' => $votingUmaBan,
            'votingAmount' => $votingAmount,

        ]);
    }

    /** 馬番をキーにオッズを返すような連想配列を作成して返却する **/
    private function formatArrayKeyUmabanValueFavorite($raceCardList) {
        $raceCardArray = array();

        foreach ($raceCardList as $raceCard) {
            $raceCardArray[$raceCard->getUmaBan()] = $raceCard->getFavourite();
        }
        return $raceCardArray;
    }
}
