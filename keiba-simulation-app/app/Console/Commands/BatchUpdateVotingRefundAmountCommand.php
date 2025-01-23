<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Scraping\BatchUpdateVotingRefundAmountService;

/***
    実行コマンド
    php artisan app:batch-update-voting-refund-amount-command
    （docker exec -it keiba_simulation_app php artisan app:batch-update-voting-refund-amount-command）

    オプションは下記で「fromRaceDate、toRaceDate」もしくは「raceId」のどちらかを指定してください
    （docker exec -it keiba_simulation_app php artisan app:batch-update-voting-refund-amount-command --fromRaceDate='2025-01-01' --toRaceDate='2025-01-31'）
***/

class BatchUpdateVotingRefundAmountCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:batch-update-voting-refund-amount-command 
                            {--fromRaceDate= : 開始日付} 
                            {--toRaceDate= : 終了日付}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '
        投票記録の払戻金のデータを更新する
    ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $batchUpdateVotingRefundAmountService = app(BatchUpdateVotingRefundAmountService::class);

        $fromRaceDate = $this->option('fromRaceDate') ?: NULL;
        $toRaceDate = $this->option('toRaceDate') ?: NULL;

        if (!(is_null($fromRaceDate) && is_null($toRaceDate))) {
            //この場合はうまくいくので処理続行
        } else {
            echo '「fromRaceDate、toRaceDate」もしくは「raceId」のどちらかを指定してください';
            return 0;
        }

        $otherWhereParams = [
            'hitStatus' => 0
        ];
        $votingRecordDatas = $batchUpdateVotingRefundAmountService->getVotingRecordByFromToDate($fromRaceDate, $toRaceDate, $otherWhereParams);

        foreach ($votingRecordDatas as $votingRecord) {
            $batchUpdateVotingRefundAmountService->updateVotingRefundAmount($votingRecord);
        }

        $this->info('バッチ処理が成功しました！');

    }
}