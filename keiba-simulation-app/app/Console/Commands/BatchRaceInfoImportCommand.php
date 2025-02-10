<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Scraping\BatchRaceInfoImportService;

/***
    実行コマンド
    php artisan app:batch-race-info-import-command
    （docker exec -it keiba_simulation_app php artisan app:batch-race-info-import-command）

    オプションは下記で「fromRaceDate、toRaceDate」もしくは「raceId」のどちらかを指定してください
    （docker exec -it keiba_simulation_app php artisan app:batch-race-info-import-command --fromRaceDate='2025-01-01' --toRaceDate='2025-01-01'）
***/

class BatchRaceInfoImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:batch-race-info-import-command 
                            {--fromRaceDate= : 開始日付} 
                            {--toRaceDate= : 終了日付}
                            {--raceId= : レースID}
                            {--startRaceNum= : 集計をスタートするレース番号}
                            {--endRaceNum= : 集計を終了するレース番号}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '
        レース情報をnetkeibaのサイトから取得して、DBにインサートする。
    ';

    /** 定数 **/
    const DEFAULT_START_RACE_NUM = 1;
    const DEFAULT_END_RACE_NUM = 12;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $batchRaceInfoImportService = app(BatchRaceInfoImportService::class);

        $fromRaceDate = $this->option('fromRaceDate') ?: NULL;
        $toRaceDate = $this->option('toRaceDate') ?: NULL;
        $optionRaceId = $this->option('raceId') ?: NULL;
        $startRaceNum = $this->option('startRaceNum') ?: self::DEFAULT_START_RACE_NUM;
        $endRaceNum = $this->option('endRaceNum') ?: self::DEFAULT_END_RACE_NUM;

        if (!(is_null($fromRaceDate) && is_null($toRaceDate)) || !is_null($optionRaceId)) {
            //この場合はうまくいくので処理続行
        } else {
            echo '「fromRaceDate、toRaceDate」もしくは「raceId」のどちらかを指定してください';
            return 0;
        }

        $batchRaceInfoImportService->raceLoopExec($fromRaceDate, $toRaceDate, $optionRaceId, $startRaceNum, $endRaceNum);

        $this->info('バッチ処理が成功しました！!');

    }
}