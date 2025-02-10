<?php

namespace App\Services\General;

use App\Services\General\GeneralBase;
use App\Services\Scraping\BatchRaceInfoImportService;

class RaceBatchGeneral extends GeneralBase
{
    /** 日付の会場情報からレース数を取得する **/
    public function getRaceCountByDateAndJyo($raceSchedule) {
        $batchRaceInfoImportService = app(BatchRaceInfoImportService::class);

        $jyoCd = $raceSchedule->getJyoCd();
        list($year, $month, $day) = explode('-', $raceSchedule->getRaceDate()->format('Y-m-d'));

        $raceCount = 0;
        for ($raceNum=0; $raceNum < 12; $raceNum++) {
            $raceIdForGetRaceCount = $year . $jyoCd . $month . $day . str_pad($raceNum, 2, '0', STR_PAD_LEFT);
            $raceCount = $batchRaceInfoImportService->getCountOfRaces($raceIdForGetRaceCount);

            if ($raceCount != 0) {
                break;
            }
        }

        return $raceCount;
    }
}