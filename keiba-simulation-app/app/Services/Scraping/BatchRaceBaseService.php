<?php

namespace App\Services\Scraping;

use App\Services\Crud\RaceScheduleService;
use App\Services\General\RaceBatchGeneral;
use App\Services\Scraping\ScrapingBase;

class BatchRaceBaseService extends ScrapingBase
{
    public function raceLoopExec($fromRaceDate, $toRaceDate, $optionRaceId, $startRaceNum, $endRaceNum)
    {
        $raceScheduleService = app(RaceScheduleService::class);
        $baceBatchGeneral = app(RaceBatchGeneral::class);

        try {
            // もしレースIDがオプションで指定されていたらそれをもとに処理を行う
            if (!is_null($optionRaceId)) {
                $this->mainExec($optionRaceId);
            }

            // レーススケジュールデータのループを回す
            $raceScheduleList = $raceScheduleService->getRaceSchedulesByDate($fromRaceDate, $toRaceDate);
            foreach ($raceScheduleList as $raceSchedule) {
                list($year, $month, $day) = explode('-', $raceSchedule->getRaceDate()->format('Y-m-d'));
                $raceCount = $baceBatchGeneral->getRaceCountByDateAndJyo($raceSchedule);

                for ($raceNum = $startRaceNum; $raceNum <= $raceCount; $raceNum++) {
                    if ($raceNum === $endRaceNum) {
                        break;
                    }

                    $raceId = $year . $raceSchedule->getJyoCd() . $month . $day . str_pad($raceNum, 2, '0', STR_PAD_LEFT);
                    $this->mainExec($raceId);
                }
            }
        } catch (\Exception $ex) {
            return 0;
        }
    }

    public function mainExec($raceId) {
        // 処理は子クラスに実装
    }
}