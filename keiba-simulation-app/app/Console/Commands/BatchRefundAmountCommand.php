<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Scraping\BatchRefundAmountService;
use App\Services\Scraping\BatchRaceInfoImportService;
use App\Services\Crud\RaceScheduleService;

/***
    実行コマンド
    php artisan app:batch-refund-amount-command
    （docker exec -it keiba_simulation_app php artisan app:batch-refund-amount-command）

    オプションは下記で「fromRaceDate、toRaceDate」もしくは「raceId」のどちらかを指定してください
    （docker exec -it keiba_simulation_app php artisan app:batch-refund-amount-command --fromRaceDate='2025-01-01' --toRaceDate='2025-01-31'）
***/

class BatchRefundAmountCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:batch-refund-amount-command
                            {--fromRaceDate= : 開始日付} 
                            {--toRaceDate= : 終了日付}
                            {--raceId= : レースID}';

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
        $batchRaceInfoImportService = app(BatchRaceInfoImportService::class);
        $raceScheduleService = app(RaceScheduleService::class);
        $batchRefundAmountService = app(BatchRefundAmountService::class);

        $fromRaceDate = $this->option('fromRaceDate') ?: NULL;
        $toRaceDate = $this->option('toRaceDate') ?: NULL;
        $optionRaceId = $this->option('raceId') ?: NULL;

        if (!(is_null($fromRaceDate) && is_null($toRaceDate)) || !is_null($optionRaceId)) {
            //この場合はうまくいくので処理続行
        } else {
            echo '「fromRaceDate、toRaceDate」もしくは「raceId」のどちらかを指定してください';
            return 0;
        }

        $raceScheduleList = $raceScheduleService->getRaceSchedulesByDate($fromRaceDate, $toRaceDate);

        // もしレースIDがオプションで指定されていたらそれをもとに処理を行う
        if (!is_null($optionRaceId)) {
            try {
                $year = substr($optionRaceId, 0, 4);
                $jyoCd = substr($optionRaceId, 4, 2);
                $month = substr($optionRaceId, 6, 2);
                $day = substr($optionRaceId, 8, 2);
                $raceNum = substr($optionRaceId, 10, 2);

                //

                return 1;
            } catch (\Exception $ex) {
                echo 'BatchRefundAmountCommandの実行に失敗しました';
                return 0;
            }
        }

        // レーススケジュールデータから、DBにレース情報を登録する
        foreach ($raceScheduleList as $raceSchedule) {
            $jyoCd = $raceSchedule->getJyoCd();
            list($year, $month, $day) = explode('-', $raceSchedule->getRaceDate()->format('Y-m-d'));

            try {
                $tmpRaceNum = 1;
                $raceCount = 0;
                $loopCnt = 0;

                // レース数を取得する（raceIdの作成に何レース目かの情報が必要なため）
                while ($raceCount === 0 || $loopCnt <= 12) {
                    $loopCnt++;

                    $raceIdForGetRaceCount = $year . $jyoCd . $month . $day . str_pad($tmpRaceNum, 2, '0', STR_PAD_LEFT);
                    $raceCount = $batchRaceInfoImportService->getCountOfRaces($raceIdForGetRaceCount);
                }

                for ($raceNum = 1; $raceNum <= $raceCount; $raceNum++) {
                    $raceId = $year . $jyoCd . $month . $day . str_pad($raceNum, 2, '0', STR_PAD_LEFT);

                    $refundAmountResult = $batchRefundAmountService->getLocalRaceRefundAmountByNetkeiba($raceId);

                    $raceInfoCheckParams = [
                        'raceDate' => new \DateTime($year . '-' . $month . '-' . $day),
                        'jyoCd' => $jyoCd,
                        'raceNum' => $raceNum,
                    ];

                    $batchRefundAmountService->insertRaceRefundAmount($refundAmountResult, $raceInfoCheckParams);

                }

            } catch (\Exception $ex) {
                echo 'BatchRefundAmountCommandの実行に失敗しました';
            }
        }

        $this->info('バッチ処理が成功しました！');
    }
}