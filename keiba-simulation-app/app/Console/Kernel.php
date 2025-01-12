<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Artisanコマンドの登録
     */
    protected $commands = [
        \App\Console\Commands\BatchRaceScheduleCommand::class,
    ];

    /**
     * 定期実行タスクの定義
     */
    protected function schedule(Schedule $schedule)
    {
        // 例: 毎日午前2時にバッチを実行
        // $schedule->command('app:batch-race-schedule-command')->dailyAt('02:00');
    }

    /**
     * アプリケーションのコンソールコマンドを登録
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}