<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Scraping\BatchRaceInfoImportService;

/***
    実行コマンド
    php artisan app:batch-race-info-import-command
    （docker exec -it keiba_simulation_app php artisan app:batch-race-info-import-command）
***/

class BatchRaceInfoImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:batch-race-info-import-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '
        レース情報をnetkeibaのサイトから取得して、DBにインサートする。
    ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $batchRaceInfoImportService = app(BatchRaceInfoImportService::class);
        $year = '2025';
        $month = '01';
        $day  = '15';
        $raceNum = '08';
        $jyoCd = '44';
        $raceId = $year . $jyoCd . $month . $day . str_pad($raceNum, 2, '0', STR_PAD_LEFT);

        $raceInfoData = $batchRaceInfoImportService->getLocalRaceInfoByNetkeiba($raceId);

        if (!empty($raceInfoData)) {
            $raceInfoCheckParams = [
                'raceDate' => new \DateTime($year . '-' . $month . '-' . $day),
                'jyoCd' => $jyoCd,
                'raceNum' => $raceNum,
            ];

            $batchRaceInfoImportService->insertRaceInfoCard($raceInfoData, $raceInfoCheckParams);

            $this->info('バッチ処理が成功しました！');
        } else {
            $this->info('データの取得に失敗しました');
        }
    }
}