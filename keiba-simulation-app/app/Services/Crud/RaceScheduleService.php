<?php

namespace App\Services\Crud;

use Doctrine\ORM\EntityManagerInterface;
use App\Entities\RaceSchedule;
use App\Services\Crud\CrudBase;

require 'vendor/autoload.php';

class RaceScheduleService extends CrudBase
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
    }

    /** race_scheduleテーブルのデータ一覧を取得する **/
    public function getAllRaceSchedules()
    {
        return $this->entityManager->getRepository(RaceSchedule::class)->findAll();
    }

    /** IDからrace_scheduleテーブルのデータを取得する **/
    public function getRaceSchedule($id)
    {
        return $this->entityManager->getRepository(RaceSchedule::class)->find($id);
    }

    /** 日付に応じてrace_scheduleデータを取得する **/
    public function getRaceSchedulesByDate($fromRaceDate, $toRaceDate)
    {
        return $this->entityManager->getRepository(RaceSchedule::class)
                    ->getRaceSchedulesByDate($fromRaceDate, $toRaceDate);
    }

    /** ユニークなカラムを指定してデータを1つ取得する **/
    public function getRaceScheduleByUniqueColumn($whereParams) {
        return $this->entityManager->getRepository(RaceSchedule::class)->findOneBy($whereParams);
    }

    /** race_scheduleにデータを作成する **/
    public function createRaceSchedule(array $data)
    {
        $raceSchedule = new RaceSchedule();

        $raceSchedule->setRaceDate(isset($data['race_date']) ? $data['race_date'] : $data['raceDate']);
        $raceSchedule->setJyoCd(isset($data['jyo_cd']) ? $data['jyo_cd'] : $data['jyoCd']);

        $this->entityManager->persist($raceSchedule);
        $this->entityManager->flush();

    }

    /** race_scheduleのデータを更新する **/
    public function updateraceSchedule($id, array $data)
    {
        $raceScheduleRepository = $this->entityManager->getRepository(RaceSchedule::class);
        $raceSchedule = $raceScheduleRepository->find($id);

        $raceSchedule->setRaceDate(isset($data['race_date']) ? $data['race_date'] : $data['raceDate']);
        $raceSchedule->setJyoCd(isset($data['jyo_cd']) ? $data['jyo_cd'] : $data['jyoCd']);

        $this->entityManager->persist($raceSchedule);
        $this->entityManager->flush();
    }

    /** race_scheduleのデータを物理削除する **/
    public function deleteRaceSchedule($id)
    {
        $raceScheduleRepository = $this->entityManager->getRepository(RaceSchedule::class);
        $raceSchedule = $raceScheduleRepository->find($id);

        if (!empty($raceSchedule)) {
            $this->entityManager->remove($raceSchedule);
            $this->entityManager->flush();
        }
    }

}