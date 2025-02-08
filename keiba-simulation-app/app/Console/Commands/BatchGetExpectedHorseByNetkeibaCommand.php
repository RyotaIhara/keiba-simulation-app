<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Scraping\BatchGetExpectedHorseByNetkeibaService;
use App\Services\Scraping\BatchRaceInfoImportService;
use App\Services\Crud\RaceScheduleService;

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

        $fromRaceDate = $this->option('fromRaceDate') ?: NULL;
        $toRaceDate = $this->option('toRaceDate') ?: NULL;
        $optionRaceId = $this->option('raceId') ?: NULL;
        $startRaceNum = $this->option('startRaceNum') ?: self::DEFAULT_START_RACE_NUM;
        $endRaceNum = $this->option('endRaceNum') ?: self::DEFAULT_END_RACE_NUM;

        $batchGetExpectedHorseByNetkeibaService = app(BatchGetExpectedHorseByNetkeibaService::class);
        $raceScheduleService = app(RaceScheduleService::class);
        $batchRaceInfoImportService = app(BatchRaceInfoImportService::class);

        if (!(is_null($fromRaceDate) && is_null($toRaceDate)) || !is_null($optionRaceId)) {
            //この場合はうまくいくので処理続行
        } else {
            echo '「fromRaceDate、toRaceDate」もしくは「raceId」のどちらかを指定してください';
            return 0;
        }

        // もしレースIDがオプションで指定されていたらそれをもとに処理を行う
        if (!is_null($optionRaceId)) {
            try {
                $year = substr($optionRaceId, 0, 4);
                $jyoCd = substr($optionRaceId, 4, 2);
                $month = substr($optionRaceId, 6, 2);
                $day = substr($optionRaceId, 8, 2);
                $raceNum = substr($optionRaceId, 10, 2);

                $expectHorses = $batchGetExpectedHorseByNetkeibaService->loginAndGetExpectedHorseLocalRaceByNetkeiba($optionRaceId);

                return 1;
            } catch (\Exception $ex) {
                echo 'BatchGetExpectedHorseByNetkeibaCommandの実行に失敗しました';
                return 0;
            }
        }

        // レーススケジュールデータから、DBに予想馬を登録する
        $raceScheduleList = $raceScheduleService->getRaceSchedulesByDate($fromRaceDate, $toRaceDate);
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

                // 集計終了レースが指定されている場合
                if ($endRaceNum !== 0) {
                    $raceCount = $endRaceNum;
                }

                for ($raceNum = $startRaceNum; $raceNum <= $raceCount; $raceNum++) {
                    $raceId = $year . $jyoCd . $month . $day . str_pad($raceNum, 2, '0', STR_PAD_LEFT);
                    $expectHorses = $batchGetExpectedHorseByNetkeibaService->loginAndGetExpectedHorseLocalRaceByNetkeiba($raceId);

                    $raceInfoCheckParams = [
                        'raceDate' => new \DateTime($year . '-' . $month . '-' . $day),
                        'jyoCd' => $jyoCd,
                        'raceNum' => $raceNum,
                    ];
                    $batchGetExpectedHorseByNetkeibaService->insertExpectedHorseData($expectHorses, $raceInfoCheckParams);

                }

            } catch (\Exception $ex) {
                echo 'BatchGetExpectedHorseByNetkeibaCommandの実行に失敗しました' . $ex->getMessage();
            }
        }

        $this->info('「batch-get-horse-race-expected-command」バッチ処理が完了しました！');
    }
}