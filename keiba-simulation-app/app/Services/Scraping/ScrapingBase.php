<?php

namespace App\Services\Scraping;

use App\Services\Base;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

require 'vendor/autoload.php';

class ScrapingBase extends Base
{
    protected $client;

    public function getCrawler($scrapingUrl)
    {
        $this->client = new Client();

        $response = $this->client->request('GET', $scrapingUrl);
        $html = (string) $response->getBody();

        return new Crawler($html);
    }

}