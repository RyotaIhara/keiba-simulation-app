<?php

namespace App\Services\Scraping;

use App\Services\Scraping\ScrapingBase;
use App\Services\Crud\RaceInfoService;
use App\Services\Crud\RaceRefundAmountService;
use App\Services\Crud\HowToBuyMstService;
require 'vendor/autoload.php';

class BatchRefundAmountService extends ScrapingBase
{
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
                    $result['Wakuren']['Result'] = $this->formatRefundAmountData($trNode);
                    $result['Wakuren']['Payout'] =$trNode->filter('td.Payout')->text();
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
            echo "データの取得に失敗しました\n" . $e;

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
                echo "race_infoにデータが存在しません";
                return False; 
            }

            $collectionForInsert = [];

            foreach ($refundAmountResult as $key => $value) {
                $howToBuyMstService = app(HowToBuyMstService::class);
                $whereParams = [
                    'howToBuyCode' => $key
                ];
                $howToBuyMstData = $howToBuyMstService->getHowToBuyMstByUniqueColumn($whereParams);

                $createParamsForRaceRefundAmount = array(
                    'race_info_id' => $raceInfo->getId(),
                    'how_to_buy_mst_id' => $howToBuyMstData->getId(),
                    'how_to_buy_code' => $key
                );

                $pattern = 1;
                if ($key === 'Fukusho') {
                    foreach ($value['Result'][0] as $resultUmaBan) {

                        $createParamsForRaceRefundAmount['pattern'] = $pattern;
                        $createParamsForRaceRefundAmount['result_uma_ban'] = $resultUmaBan;
                        $createParamsForRaceRefundAmount['refund_amount'] = str_replace([",", "円"], "", $value['Payout'][$pattern-1]);
                        $pattern++;

                        array_push($collectionForInsert, $createParamsForRaceRefundAmount);
                    }
                }
                else if ($key === 'wide') {
                    foreach ($value['Result'] as $resultUmaBan) {
                        $createParamsForRaceRefundAmount['pattern'] = $pattern;
                        $createParamsForRaceRefundAmount['result_uma_ban'] = implode(",", $resultUmaBan);
                        $createParamsForRaceRefundAmount['refund_amount'] = str_replace([",", "円"], "", $value['Payout'][$pattern-1]);
                        $pattern++;

                        array_push($collectionForInsert, $createParamsForRaceRefundAmount);
                    }
                } else {
                    $createParamsForRaceRefundAmount['pattern'] = $pattern;
                    $createParamsForRaceRefundAmount['result_uma_ban'] = implode(",", $value['Result'][0]);
                    $createParamsForRaceRefundAmount['refund_amount'] = str_replace([",", "円"], "", $value['Payout']);

                    array_push($collectionForInsert, $createParamsForRaceRefundAmount);
                }
            }

            foreach ($collectionForInsert as $createParamsForRaceRefundAmount) {
                $raceInfoService = app(RaceInfoService::class);
                $raceInfo = $raceInfoService->getRaceInfo($createParamsForRaceRefundAmount['race_info_id']);

                $whereParamsForHowToBuyMst = [
                    'howToBuyCode' => $createParamsForRaceRefundAmount['how_to_buy_code'],
                ];
                $howToBuyMstData = $howToBuyMstService->getHowToBuyMstByUniqueColumn($whereParamsForHowToBuyMst);

                $whereParamsForRaceRefundAmount = [
                    'raceInfo' => $raceInfo,
                    'howToBuyMst' => $howToBuyMstData,
                    'pattern' => $createParamsForRaceRefundAmount['pattern']
                ];
                $raceRefundAmountData = $raceRefundAmountService->getRaceRefundAmountByUniqueColumn($whereParamsForRaceRefundAmount);
                if (empty($raceRefundAmountData)) {
                    $raceRefundAmountService->createRaceRefundAmount($createParamsForRaceRefundAmount);
                } else {
                    echo 'すでに' . 'race_info_id：' . $raceInfo->getId() . ', how_to_buy_mst_code：' . $createParamsForRaceRefundAmount['how_to_buy_code'] . ', pattern：' . $pattern . 'のデータは存在します';
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