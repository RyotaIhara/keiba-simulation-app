<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Scraping\BatchRefundAmountService;

/***
    実行コマンド
    php artisan app:batch-refund-amount-command
    （docker exec -it keiba_simulation_app php artisan app:batch-refund-amount-command）
***/

class BatchRefundAmountCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:batch-refund-amount-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '
        払戻金のデータをnetkeibaのサイトから取得して、race_refund_amountテーブルにインサートする。
    ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $batchRefundAmountService = app(BatchRefundAmountService::class);
        $raceId = '202544011506';
        $refundAmountResult = $batchRefundAmountService->getLocalRaceRefundAmountByNetkeiba($raceId);

        var_dump($refundAmountResult);

        $this->info('バッチ処理が完了しました！');
    }
}