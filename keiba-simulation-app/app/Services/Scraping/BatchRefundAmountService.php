<?php

namespace App\Services\Scraping;

use App\Services\Scraping\BatchRaceBaseService;
use App\Services\Crud\RaceInfoService;
use App\Services\Crud\RaceRefundAmountService;
use App\Services\Crud\BettingTypeMstService;
require 'vendor/autoload.php';

class BatchRefundAmountService extends BatchRaceBaseService
{
    /** BatchRefundAmountCommandのメイン処理をここに実装 **/
    public function mainExec($raceId, $otherParams) {
        $year = substr($raceId, 0, 4);
        $jyoCd = substr($raceId, 4, 2);
        $month = substr($raceId, 6, 2);
        $day = substr($raceId, 8, 2);
        $raceNum = substr($raceId, 10, 2);

        $refundAmountResult = $this->getLocalRaceRefundAmountByNetkeiba($raceId);

        $raceInfoCheckParams = [
            'raceDate' => new \DateTime($year . '-' . $month . '-' . $day),
            'jyoCd' => $jyoCd,
            'raceNum' => $raceNum,
        ];

        $this->insertRaceRefundAmount($refundAmountResult, $raceInfoCheckParams);
    }

    /** 地方競馬の払戻金情報を取得する **/
    public function getLocalRaceRefundAmountByNetkeiba($raceId) {
        $scrapingUrl = self::$NETKEIBA_LOCAL_RACE_DOMAIN_URL . 'race/result.html?race_id=' . $raceId;

        $crawler = $this->getCrawler($scrapingUrl);

        $result = array();

        try {
            $crawler->filter('table.Payout_Detail_Table')->each(function ($node) use(&$result) {
                $node->filter('tr.Tansho')->each(function ($trNode) use(&$result) {
                    $result['Tansho']['Result'] = $this->formatRefundAmountData($trNode);
                    $result['Tansho']['Payout'] =$trNode->filter('td.Payout')->text();
                });

                $node->filter('tr.Fukusho')->each(function ($trNode) use(&$result) {
                    $result['Fukusho']['Result'] = $this->formatRefundAmountData($trNode);
                    $result['Fukusho']['Payout'] = explode('<br>', $trNode->filter('td.Payout span')->html());
                });

                $node->filter('tr.Wakuren')->each(function ($trNode) use(&$result) {
                    $result['Wakuren']['Result'] = $this->formatRefundAmountData($trNode);
                    $result['Wakuren']['Payout'] =$trNode->filter('td.Payout')->text();
                });

                $node->filter('tr.Umaren')->each(function ($trNode) use(&$result) {
                    $result['Umaren']['Result'] = $this->formatRefundAmountData($trNode);
                    $result['Umaren']['Payout'] =$trNode->filter('td.Payout')->text();
                });

                $node->filter('tr.Wide')->each(function ($trNode) use(&$result) {
                    $result['wide']['Result'] = $this->formatRefundAmountData($trNode);
                    $result['wide']['Payout'] = explode('<br>', $trNode->filter('td.Payout span')->html());
                });

                $node->filter('tr.Wakutan')->each(function ($trNode) use(&$result) {
                    $result['Wakutan']['Result'] = $this->formatRefundAmountData($trNode);
                    $result['Wakutan']['Payout'] =$trNode->filter('td.Payout')->text();
                });

                $node->filter('tr.Umatan')->each(function ($trNode) use(&$result) {
                    $result['Umatan']['Result'] = $this->formatRefundAmountData($trNode);
                    $result['Umatan']['Payout'] =$trNode->filter('td.Payout')->text();
                });

                $node->filter('tr.Fuku3')->each(function ($trNode) use(&$result) {
                    $result['Fuku3']['Result'] = $this->formatRefundAmountData($trNode);
                    $result['Fuku3']['Payout'] =$trNode->filter('td.Payout')->text();
                });

                $node->filter('tr.Tan3')->each(function ($trNode) use(&$result) {
                    $result['Tan3']['Result'] = $this->formatRefundAmountData($trNode);
                    $result['Tan3']['Payout'] =$trNode->filter('td.Payout')->text();
                });
            });
        } catch ( \Exception $e ) {
            echo "データの取得に失敗しました\n";

            return [];
        }

        return $result;
    }

    /** 中央競馬の払戻金情報を取得する **/
    public function getCentralRaceRefundAmountByNetkeiba($raceId) {
        //
    }

    /** race_refund_amountにデータをインサートする **/
    public function insertRaceRefundAmount($refundAmountResult, $raceInfoCheckParams) {
        try {
            $raceInfoService = app(RaceInfoService::class);
            $raceRefundAmountService = app(RaceRefundAmountService::class);

            $raceInfo = $raceInfoService->getRaceInfoByUniqueColumn($raceInfoCheckParams);
            if (empty($raceInfo)) {
                echo "race_infoにデータが存在しません \n";
                return False; 
            }

            // インサート用配列を作成するループ
            $collectionForInsert = [];
            foreach ($refundAmountResult as $key => $value) {
                $bettingTypeMstService = app(BettingTypeMstService::class);
                $whereParams = [
                    'bettingTypeCode' => $key
                ];
                $bettingTypeMst = $bettingTypeMstService->getBettingTypeMstByUniqueColumn($whereParams);

                $createParamsForRaceRefundAmount = array(
                    'raceInfo' => $raceInfo,
                    'bettingTypeMst' => $bettingTypeMst
                );

                $pattern = 1;
                if ($key === 'Fukusho') {
                    foreach ($value['Result'][0] as $resultUmaBan) {

                        $createParamsForRaceRefundAmount['pattern'] = $pattern;
                        $createParamsForRaceRefundAmount['resultUmaBan'] = $resultUmaBan;
                        $createParamsForRaceRefundAmount['refundAmount'] = str_replace([",", "円"], "", $value['Payout'][$pattern-1]);
                        $pattern++;

                        array_push($collectionForInsert, $createParamsForRaceRefundAmount);
                    }
                }
                else if ($key === 'wide') {
                    foreach ($value['Result'] as $resultUmaBan) {
                        $createParamsForRaceRefundAmount['pattern'] = $pattern;
                        $createParamsForRaceRefundAmount['resultUmaBan'] = implode(",", $resultUmaBan);
                        $createParamsForRaceRefundAmount['refundAmount'] = str_replace([",", "円"], "", $value['Payout'][$pattern-1]);
                        $pattern++;

                        array_push($collectionForInsert, $createParamsForRaceRefundAmount);
                    }
                } else {
                    $createParamsForRaceRefundAmount['pattern'] = $pattern;
                    $createParamsForRaceRefundAmount['resultUmaBan'] = implode(",", $value['Result'][0]);
                    $createParamsForRaceRefundAmount['refundAmount'] = str_replace([",", "円"], "", $value['Payout']);

                    array_push($collectionForInsert, $createParamsForRaceRefundAmount);
                }
            }

            // インサート作業実施
            foreach ($collectionForInsert as $createParamsForRaceRefundAmount) {
                $raceInfo = $createParamsForRaceRefundAmount['raceInfo'];
                $bettingTypeMst = $createParamsForRaceRefundAmount['bettingTypeMst'];
                $pattern = $createParamsForRaceRefundAmount['pattern'];

                $whereParamsForRaceRefundAmount = [
                    'raceInfo' => $raceInfo,
                    'bettingTypeMst' => $bettingTypeMst,
                    'pattern' => $pattern
                ];
                $raceRefundAmountData = $raceRefundAmountService->getRaceRefundAmountByUniqueColumn($whereParamsForRaceRefundAmount);

                // データがなければ作成
                if (empty($raceRefundAmountData)) {
                    $raceRefundAmountService->createRaceRefundAmount($createParamsForRaceRefundAmount);
                } else {
                    echo 'すでに' . 'race_info_id：' . $raceInfo->getId() . ', betting_type_name' . $bettingTypeMst->getBettingTypeName() . ', pattern：' . $pattern . 'のデータは存在します', " \n";
                }
            }

        } catch (\Exception $e) {
            echo "データの作成に失敗しました\n" . $e;

            return False;
        }

        return True;
    }

    /** 払戻金データを整形する **/
    protected function formatRefundAmountData($trNode) {
        $ulNode = $trNode->filter('ul');
        $result = [];
        if (empty($ulNode->text(''))) {
            $tmpResult = [];
            $trNode->filter('div span')->each(function ($divNode) use (&$tmpResult) {
                $divText = $divNode->text('');
                if ($divText !== "") {
                    $tmpResult[] = $divText; // 3連単などと合わせるために多重配列の構造にしておく
                }
            });
            $uniqueResult = array_unique($tmpResult);
            $result[] = $uniqueResult;
        } else {
            $trNode->filter('ul')->each(function ($ulNode) use (&$result) {
                $tmpResult = [];
                $ulNode->filter('li span')->each(function ($liNode) use (&$tmpResult) {
                    $liText = $liNode->text('');
                    if ($liText !== "") {
                        $tmpResult[] = $liText;
                    }
                });
                $uniqueResult = array_unique($tmpResult);
                $result[] = $uniqueResult;
            });
        }

        return $result;
    }

}