<?php

namespace App\Services\Scraping;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Symfony\Component\DomCrawler\Crawler;

class BatchGetExpectedHorseByNetkeibaService
{
    protected $client;
    protected $cookieJar;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://regist.netkeiba.com/',
            'cookies' => true, // クッキーを保持
        ]);
        $this->cookieJar = new CookieJar();
    }

    /**
     * Netkeiba にログインして推奨馬を取得する
     */
    public function loginAndGetExpectedHorseLocalRaceByNetkeiba($loginId, $password, $raceId)
    {
        // 1. ログインリクエスト
        $loginUrl = 'https://regist.netkeiba.com/account/';
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
                $horseNumber = $node->filter('span.Umaban_Num')->text();
                $horseName = $node->filter('a.data_top_horse_link')->text();
                $horseLink = $node->filter('a.data_top_horse_link')->attr('href');

                $featureDatas = [];
                $node->filter('dd.PickupDataBox ul li')->each(function ($li) use (&$featureDatas) {
                    $featureDatas[] = $li->text();
                });

                $results[] = [
                    'horseNumber' => $horseNumber,
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
}
