<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Scraping\BatchGetExpectedHorseByNetkeibaService;

/***
    実行コマンド
    php artisan aapp:batch-get-expected-horse-netkeiba-command
    （docker exec -it keiba_simulation_app php artisan app:batch-get-expected-horse-netkeiba-command）

    （docker exec -it keiba_simulation_app php artisan app:batch-get-expected-horse-netkeiba-command --fromRaceDate='2025-01-01' --toRaceDate='2025-01-01'）
***/

class BatchGetExpectedHorseByNetkeibaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:batch-get-expected-horse-netkeiba-command
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
        netkeibaの推奨馬をサイトから取得して、DBに保存するバッチ
    ';

    /** 定数 **/
    const DEFAULT_START_RACE_NUM = 1;
    const DEFAULT_END_RACE_NUM = 12;

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $fromRaceDate = $this->option('fromRaceDate') ?: null;
        $toRaceDate = $this->option('toRaceDate') ?: null;
        $optionRaceId = $this->option('raceId') ?: null;
        $startRaceNum = $this->option('startRaceNum') ?: self::DEFAULT_START_RACE_NUM;
        $endRaceNum = $this->option('endRaceNum') ?: self::DEFAULT_END_RACE_NUM;

        $batchGetExpectedHorseByNetkeibaService = app(BatchGetExpectedHorseByNetkeibaService::class);

        if (!(is_null($fromRaceDate) && is_null($toRaceDate)) || !is_null($optionRaceId)) {
            //この場合はうまくいくので処理続行
        } else {
            echo '「fromRaceDate、toRaceDate」もしくは「raceId」のどちらかを指定してください';
            return 0;
        }

        $batchGetExpectedHorseByNetkeibaService->raceLoopExec($fromRaceDate, $toRaceDate, $optionRaceId, $startRaceNum, $endRaceNum, $otherParams=[]);

        $this->info('バッチ処理が成功しました！');
    }
}