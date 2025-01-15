<?php

namespace App\Services\Scraping;

use App\Services\Scraping\ScrapingBase;

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
                    $result['tanshoResult'] = $this->formatRefundAmountData($trNode);
                });

                $node->filter('tr.Fukusho')->each(function ($trNode) use(&$result) {
                    $result['fukushoResult'] = $this->formatRefundAmountData($trNode);
                });

                $node->filter('tr.Wakuren')->each(function ($trNode) use(&$result) {
                    $result['tanshoResult'] = $this->formatRefundAmountData($trNode);
                });

                $node->filter('tr.Umaren')->each(function ($trNode) use(&$result) {
                    $result['umarenResult'] = $this->formatRefundAmountData($trNode);
                });

                $node->filter('tr.Wide')->each(function ($trNode) use(&$result) {
                    $result['wideResult'] = $this->formatRefundAmountData($trNode);
                });

                $node->filter('tr.Wakutan')->each(function ($trNode) use(&$result) {
                    $result['wakutanResult'] = $this->formatRefundAmountData($trNode);
                });

                $node->filter('tr.Umatan')->each(function ($trNode) use(&$result) {
                    $result['umatanResult'] = $this->formatRefundAmountData($trNode);
                });

                $node->filter('tr.Fuku3')->each(function ($trNode) use(&$result) {
                    $result['fuku3Result'] = $this->formatRefundAmountData($trNode);
                });

                $node->filter('tr.Tan3')->each(function ($trNode) use(&$result) {
                    $result['tan3Result'] = $this->formatRefundAmountData($trNode);
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

    /** 払戻金データを整形する **/
    function formatRefundAmountData($trNode) {
        $ulNode = $trNode->filter('ul');
        $result = [];
        if (empty($ulNode->text(''))) {
            $trNode->filter('div span')->each(function ($divNode) use (&$result) {
                $divText = $divNode->text('');
                if ($divText !== "") {
                    $result[] = $divText;
                }
            });
            $result = array_unique($result);
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