<?php

namespace App\Services\Scraping;

use App\Services\Scraping\ScrapingBase;
use App\Services\Crud\RaceInfoService;
use App\Services\Crud\RaceCardService;

class BatchRaceInfoImportService extends ScrapingBase
{
    /** Netkeibaのサイトから地方競馬のレース情報を取得する **/
    public function getLocalRaceInfoByNetkeiba($raceId) {
        $scrapingUrl = self::$NETKEIBA_LOCAL_RACE_DOMAIN_URL . 'race/shutuba.html?race_id=' . $raceId;

        $crawler = $this->getCrawler($scrapingUrl);

        $raceInfo = array();
        try {
            $raceName = $crawler->filter('div.RaceName')->text('');
            $raceInfo['race_name'] =trim(preg_replace('/\s+/', ' ', $raceName));

            // レースそのものの情報を取得
            $crawler->filter('div.RaceData01')->each(function ($node) use (&$raceInfo) {
                $timeText = $node->text();

                $distance = $node->filter('span')->eq(0)->text();

                if (preg_match('/^(ダ|芝|障|直)(\d+m)$/', $distance, $matches)) {
                    $raceInfo['course_type'] = $matches[1];
                    $raceInfo['distance'] = $distance = $matches[2];
                    if (preg_match('/^(\d+)m$/', trim($distance), $matches2)) {
                        $raceInfo['distance'] = intval($distance);
                    }
                } else {
                    echo "データが正しく解析できませんでした。(1)\n";
                }

                // コース
                preg_match('/\((右|左)\)/', $timeText, $courseMatch);
                $raceInfo['rotation'] = isset($courseMatch[0]) ? str_replace(['(', ')'], '', $courseMatch[0]) : '';

                // 天候
                preg_match('/天候:([^\s<]+)/', $timeText, $weatherMatch);
                $raceInfo['weather'] = $weatherMatch[1] ?? '';
            
                // 馬場状態
                $trackCondition = $node->filter('span.Item04')->text();
                $raceInfo['baba_state'] = str_replace('/ 馬場:', '', trim($trackCondition));
            });


            // 出走馬情報を取得
            $crawler->filter('tr.HorseList')->each(function ($parentRow) use (&$horceInfoList) {
                $wakuBan = $parentRow->filter('td[class^="Waku"]')->text('');
                $umaBan = $parentRow->filter('td[class^="Umaban"]')->text('');
                $horseName = $parentRow->filter('td.HorseInfo span.HorseName a')->text('');
                $sexAge = $parentRow->filter('td')->eq(1)->text('');
                $jockey = $parentRow->filter('td.Jockey a')->text('');
                $trainer = $parentRow->filter('td.Trainer')->text('');

                $weight = NULL;
                $weightGainLoss = NULL;
                $favourite = NULL;
                $winOdds = NULL;

                $isCancel = False;
                if ($parentRow->filter('td.Cancel_Txt')->count() > 0) {
                    $isCancel = True;
                } else {
                    $weightInfoOrigin = $parentRow->filter('td.Weight')->text('');
                    if (preg_match('/^(\d+)\(([^)]+)\)$/', $weightInfoOrigin, $matches)) {
                        $weight = $matches[1];
                        $weightGainLoss = $matches[2];
                        $favourite = $parentRow->filter('td.Popular.Txt_C')->text('');
                        $winOdds = $parentRow->filter('td.Popular.Txt_R')->text('');
                    } else {
                        echo "データが正しく解析できませんでした。(2)\n";
                    }
                }

                $horceInfoList[] = [
                    'waku_ban' => $wakuBan,
                    'uma_ban' => $umaBan,
                    'horse_name' => $horseName,
                    'sex_age' => $sexAge,
                    'weight' => $weight,
                    'jockey_name' => $jockey,
                    'favourite' => $favourite,
                    'win_odds' => $winOdds,
                    'trainer' => $trainer,
                    'weight_gain_loss' => $weightGainLoss,
                    'is_cancel' => $isCancel
                ];
            });
        } catch (\Exception $e) {
            echo "データの取得に失敗しました\n" . $e;

            return [];
        }

        $results = array(
            'raceInfo' => $raceInfo,
            'horceInfoList' => $horceInfoList,
        );

        return $results;
    }

    /** Netkeibaのサイトから中央競馬のレース情報を取得する **/
    public function getCentralRaceInfoByNetkeiba() {
        //
    }

    /** race_infoとrace_cardにデータをインサートする **/
    public function insertRaceInfoCard($raceInfoData, $raceInfoCheckParams) {
        try {
            $raceInfoService = app(RaceInfoService::class);
            $raceCardService = app(RaceCardService::class);
    
            $raceInfoArray = $raceInfoData['raceInfo'];
            $raceInfoArray['race_date'] = $raceInfoCheckParams['raceDate'];
            $raceInfoArray['jyo_cd'] = $raceInfoCheckParams['jyoCd'];
            $raceInfoArray['race_num'] = $raceInfoCheckParams['raceNum'];
            $raceInfoArray['entry_horce_count'] = count($raceInfoData['horceInfoList']);

            $raceInfo = $raceInfoService->getRaceInfoByUniqueColumn($raceInfoCheckParams);
            if (empty($raceInfo)) {
                // 新規作成
                $raceInfoService->createRaceInfo($raceInfoArray);
                $raceInfo = $raceInfoService->getRaceInfoByUniqueColumn($raceInfoCheckParams);
            } else {
                // 更新
                $raceInfoService->updateRaceInfo($raceInfo->getId(), $raceInfoArray);
            }

            foreach ($raceInfoData['horceInfoList'] as $horseInfo) {
                $horseInfo['race_info_id'] = $raceInfo->getId();
    
                $raceCardCheckParams = array(
                    'raceInfo' => $raceInfo,
                    'umaBan' => $horseInfo['uma_ban'],
                );
                $raceCard = $raceCardService->getRaceCardByUniqueColumn($raceCardCheckParams);
                if (empty($raceCard)) {
                    // 新規作成
                    $raceCardService->createRaceCard($horseInfo);
                } else {
                    // 更新
                    $raceCardService->updateRaceCard($raceCard->getId(), $horseInfo);
                }
            }
        } catch (\Exception $e) {
            echo "データの作成に失敗しました\n" . $e;

            return False;
        }

        return True;
    }

    /** レース数を取得するメソッド **/
    function getCountOfRaces($raceId) 
    {
        $results = array();

        $scrapingUrl = self::$NETKEIBA_LOCAL_RACE_DOMAIN_URL . 'race/shutuba.html?race_id=' . $raceId;
        $crawler = $this->getCrawler($scrapingUrl);

        try {
            $crawler->filter('div.RaceNumWrap ul.fc li')->each(function ($node) use (&$results) {
                $link = $node->filter('a');
                if ($link->count() > 0) {
                    $results[] = trim($link->text());
                }
            });
        } catch (\Exception $ex) {
            echo 'レース数がうまく取得できませんでした' .  $ex->getMessage() . "\n";
        }

        return count($results);

    }

}