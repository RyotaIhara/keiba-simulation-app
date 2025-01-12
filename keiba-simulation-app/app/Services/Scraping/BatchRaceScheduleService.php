<?php

namespace App\Services\Scraping;

use App\Services\Scraping\ScrapingBase;
use App\Services\Crud\RacecourseMstService;

require 'vendor/autoload.php';

class BatchRaceScheduleService extends ScrapingBase
{
    /** Netkeibaのサイトから地方競馬のカレンダー情報を取得する **/
    public function getLocalRaceCalendarInfoByNetkeiba($year, $month) {
        $scrapingUrl = self::$NETKEIBA_LOCAL_RACE_DOMAIN_URL . 'top/calendar.html?year=' . $year . '&month=' . $month;

        $crawler = $this->getCrawler($scrapingUrl);

        $crawler->filter('td.RaceCellBox')->each(function ($node) use ($year, $month) {
            $day = $node->filter('span.Day')->count() ? $node->filter('span.Day')->text() : null;
            $day = str_pad($day, 2, '0', STR_PAD_LEFT);

            $jyoNames = $node->filter('span.JyoName')->each(function ($jyoNode) {
                return $jyoNode->text();
            });

            foreach ($jyoNames as $jyoName) {
                // 「帯広ば」だけ特殊なので整形
                if ($jyoName === '帯広ば') {
                    $jyoName = '帯広';
                }

                var_dump($jyoName);

                /*
                $racecourseMstService = new RacecourseMstService();
                $whereParams = [
                    'racecourse_name' => $jyoName . '競馬場'
                ];
                $racecourseMst = $racecourseMstService->getRacecourseMstByUniqueColumn($whereParams);

                var_dump($racecourseMst);
                */

                /*
                $racecourseMst = $getDataFunction->getRacecourseMst(
                    [
                        'racecourse_name' => $jyoName . '競馬場'
                    ]
                );

                $date = $year . '-' . $month . '-' . $day;
                $jyoCd = $result[0]['jyo_cd'];

                $params = array(
                    'race_date' => $date,
                    'jyo_cd' => $jyoCd,
                );

                if ($this->checkRaceSchedule($params, $getDataFunction)) {
                    echo "すでに" . 'race_date=' . $date . '、jyo_cd=' . $jyoCd . "のデータが存在しています。 \n";
                } else {
                    // データがなければインサート
                    $this->insertRaceSchedule($params);
                    echo 'race_date=' . $date . '、jyo_cd=' . $jyoCd . "のデータ作成に成功しました。 \n";
                }
                */
            }
        });
    }

    /** Netkeibaのサイトから中央競馬のカレンダー情報を取得する **/
    public function getCentralRaceCalendarInfoByNetkeiba() {
        //
    }

}