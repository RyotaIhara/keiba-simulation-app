<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Scraping\BatchGetExpectedHorseByNetkeibaService;

/***
    実行コマンド
    php artisan aapp:batch-get-expected-horse-netkeiba-command
    （docker exec -it keiba_simulation_app php artisan app:batch-get-expected-horse-netkeiba-command）
***/

class BatchGetExpectedHorseByNetkeibaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:batch-get-expected-horse-netkeiba-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '
        netkeibaの推奨馬をサイトから取得して、DBに保存するバッチ
    ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $batchGetExpectedHorseByNetkeibaService = app(BatchGetExpectedHorseByNetkeibaService::class);

        $raceId = '202545020511';
        //$result = $batchGetExpectedHorseByNetkeibaService->getExpectedHorseLocalRaceByNetkeiba($raceId);

        $result = $batchGetExpectedHorseByNetkeibaService->loginAndGetExpectedHorseLocalRaceByNetkeiba('ihara0531@gmail.com', 'ryota017', $raceId);

        var_dump($result);

        $this->info('「batch-get-horse-race-expected-command」バッチ処理が完了しました！');
    }
}