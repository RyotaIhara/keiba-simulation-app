<?php

namespace App\Console\Commands\Simulation;

use Illuminate\Console\Command;
use App\Services\Scraping\Simulation\BatchSimulation01Service;
use Exception;

/***
    実行コマンド
    php artisan aapp:batch-simulation01-command
    （docker exec -it keiba_simulation_app php artisan appbatch-simulation01-command）

    （docker exec -it keiba_simulation_app php artisan app:batch-simulation01-command  --userId=3 --fromRaceDate='2025-01-01' --toRaceDate='2025-01-01'）
***/

class BatchSimulation01Command extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:batch-simulation01-command
                            {--fromRaceDate= : 開始日付} 
                            {--toRaceDate= : 終了日付}
                            {--raceId= : レースID}
                            {--startRaceNum= : 集計をスタートするレース番号}
                            {--endRaceNum= : 集計を終了するレース番号}
                            {--userId= : シミュレーションするユーザーID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '
        シミュレーション1の投票データ作成バッチ
        ↓内容
        ネット競馬から推奨馬3頭を取得
        ⇒一度DBに登録（race_info_id、umaban）
        ⇒下記パターンで購入を行う
        ・オッズが10.0以下⇒単勝30000円
        ・オッズが10～20の間⇒単勝10000円＋複勝20000円
        ・オッズが20以上⇒複勝10000円
    ';

    /** 定数 **/
    const DEFAULT_START_RACE_NUM = 1;
    const DEFAULT_END_RACE_NUM = 12;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $fromRaceDate = $this->option('fromRaceDate') ?: null;
            $toRaceDate = $this->option('toRaceDate') ?: null;
            $optionRaceId = $this->option('raceId') ?: null;
            $startRaceNum = $this->option('startRaceNum') ?: self::DEFAULT_START_RACE_NUM;
            $endRaceNum = $this->option('endRaceNum') ?: self::DEFAULT_END_RACE_NUM;
            $userId = $this->option('userId') ?: null;

            $batchSimulation01Service = app(BatchSimulation01Service::class);

            if (is_null($userId)) {
                echo '「userId」を指定してください';
                return 0;
            }

            if (!(is_null($fromRaceDate) && is_null($toRaceDate)) || !is_null($optionRaceId)) {
                //この場合はうまくいくので処理続行
            } else {
                echo '「fromRaceDate、toRaceDate」もしくは「raceId」のどちらかを指定してください';
                return 0;
            }

            $otherParams = [
                'userId' => $userId
            ];
            $batchSimulation01Service->raceLoopExec($fromRaceDate, $toRaceDate, $optionRaceId, $startRaceNum, $endRaceNum, $otherParams);

            $this->info('バッチ処理が成功しました！');
        } catch(Exception $ex) {
            echo "失敗しました\n";
        }
    }
}