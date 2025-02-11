<?php

namespace App\Services\Scraping;

use App\Services\Scraping\BatchRaceBaseService;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Symfony\Component\DomCrawler\Crawler;
use App\Services\Crud\SettingServiceService;
use App\Services\Crud\RaceInfoService;
use App\Services\Crud\Simulation\NetkeibaExpectedHorseService;

class BatchGetExpectedHorseByNetkeibaService extends BatchRaceBaseService
{
    protected $client;
    protected $cookieJar;

    public function __construct()
    {
        parent::__construct();

        $this->client = new Client([
            'base_uri' => self::$LOGIN_BASE_URL,
            'cookies' => true, // クッキーを保持
        ]);
        $this->cookieJar = new CookieJar();
    }

    /** バッチのメイン処理をここに記載 **/
    public function mainExec($raceId) {
        $year = substr($raceId, 0, 4);
        $jyoCd = substr($raceId, 4, 2);
        $month = substr($raceId, 6, 2);
        $day = substr($raceId, 8, 2);
        $raceNum = substr($raceId, 10, 2);

        $expectHorses = $this->loginAndGetExpectedHorseLocalRaceByNetkeiba($raceId);

        $raceInfoCheckParams = [
            'raceDate' => new \DateTime($year . '-' . $month . '-' . $day),
            'jyoCd' => $jyoCd,
            'raceNum' => $raceNum,
        ];
        $this->insertExpectedHorseData($expectHorses, $raceInfoCheckParams);
    }

    /**
     * Netkeiba にログインして推奨馬を取得する
     */
    public function loginAndGetExpectedHorseLocalRaceByNetkeiba($raceId)
    {
        $settingService = app(SettingServiceService::class);
        $loginId = $settingService->getSettingService('netkeiba_loginId')->getSettingValue();
        $password = $settingService->getSettingService('netkeiba_password')->getSettingValue();

        // 1. ログインリクエスト
        $loginUrl = self::$LOGIN_URL;
        $loginResponse = $this->client->request('POST', $loginUrl, [
            'form_params' => [
                'pid' => 'login',
                'action' => 'auth',
                'return_url2' => '',
                'mem_tp' => '',
                'login_id' => $loginId,
                'pswd' => $password,
            ],
            'cookies' => $this->cookieJar,
        ]);

        // ログイン成功判定 (簡易的なチェック)
        if ($loginResponse->getStatusCode() !== 200) {
            throw new \Exception("ログインに失敗しました");
        }

        // 2. 認証済みの状態でスクレイピング
        return $this->getExpectedHorseLocalRaceByNetkeiba($raceId);
    }

    /**
     * Netkeiba のサイトから地方競馬のレースにおける推奨馬を取得
     */
    public function getExpectedHorseLocalRaceByNetkeiba($raceId)
    {
        $scrapingUrl = "https://nar.sp.netkeiba.com/race/data_top.html?race_id={$raceId}";

        $response = $this->client->request('GET', $scrapingUrl, [
            'cookies' => $this->cookieJar, // ログインセッションを維持
        ]);

        $html = (string) $response->getBody();
        $crawler = new Crawler($html);

        try {
            $results = [];

            $crawler->filter('div.DataPickupHorseWrap dl')->each(function ($node) use (&$results) {
                $umaBan = $node->filter('span.Umaban_Num')->text();
                $horseName = $node->filter('a.data_top_horse_link')->text();
                $horseLink = $node->filter('a.data_top_horse_link')->attr('href');

                $featureDatas = [];
                $node->filter('dd.PickupDataBox ul li')->each(function ($li) use (&$featureDatas) {
                    $featureDatas[] = $li->text();
                });

                $results[] = [
                    'umaBan' => $umaBan,
                    'horseName' => $horseName,
                    'horseLink' => $horseLink,
                    'featureDatas' => $featureDatas,
                ];
            });

            return $results;
        } catch (\Exception $e) {
            echo "データの取得に失敗しました\n" . $e;
            return [];
        }
    }

    public function insertExpectedHorseData($expectHorses, $raceInfoCheckParams) {
        $raceInfoService = app(RaceInfoService::class);
        $netkeibaExpectedHorseService = app(NetkeibaExpectedHorseService::class);

        $raceInfo = $raceInfoService->getRaceInfoByUniqueColumn($raceInfoCheckParams);
        if (empty($raceInfo)) {
            echo "race_infoにデータが存在しません \n";
            return False; 
        }

        foreach ($expectHorses as $horse) {
            $insertParams = [
                'raceInfo' => $raceInfo,
                'umaBan' => $horse['umaBan'],
                'featureDatas' =>implode(",", $horse['featureDatas']),
            ];
            $netkeibaExpectedHorseService->createNetkeibaExpectedHorse($insertParams);
        }
    }
}
