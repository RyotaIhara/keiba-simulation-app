<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Scraping\BatchRaceScheduleService;

/***
    実行コマンド
    php artisan app:batch-race-schedule-command
    （docker exec -it keiba_simulation_app php artisan app:batch-race-schedule-command）
***/

class BatchRaceScheduleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:batch-race-schedule-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '
        レーススケジュールのデータをnetkeibaのサイトから取得して、race-scheduleテーブルにインサートする。
    ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('バッチ処理が完了しました！');

        $year = '2025';
        $month = '01';
        $batchRaceScheduleService = app(BatchRaceScheduleService::class);
        $batchRaceScheduleService->getLocalRaceCalendarInfoByNetkeiba($year, $month);
    }
}