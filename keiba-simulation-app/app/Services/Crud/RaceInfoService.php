<?php

namespace App\Services\Crud;

use Doctrine\ORM\EntityManagerInterface;
use App\Entities\RaceInfo;
use App\Services\Crud\CrudBase;

require 'vendor/autoload.php';

class RaceInfoService extends CrudBase
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
    }

    /** race_infoテーブルのデータ一覧を取得する **/
    public function getAllRaceInfos()
    {
        return $this->entityManager->getRepository(RaceInfo::class)->findAll();
    }

    /** IDからrace_infoテーブルのデータを取得する **/
    public function getRaceInfo($id)
    {
        return $this->entityManager->getRepository(RaceInfo::class)->find($id);
    }

    /** ユニークなカラムを指定してデータを1つ取得する **/
    public function getRaceInfoByUniqueColumn($whereParams) {
        return $this->entityManager->getRepository(RaceInfo::class)->findOneBy($whereParams);
    }

    /** race_infoにデータを作成する **/
    public function createRaceInfo(array $data)
    {
        $raceInfo = new RaceInfo();

        $raceInfo = $this->setRaceInfo($raceInfo, $data);

        $this->entityManager->persist($raceInfo);
        $this->entityManager->flush();

    }

    /** race_infoのデータを更新する **/
    public function updateraceInfo($id, array $data)
    {
        $raceInfoRepository = $this->entityManager->getRepository(RaceInfo::class);
        $raceInfo = $raceInfoRepository->find($id);

        $raceInfo = $this->setRaceInfo($raceInfo, $data);

        $this->entityManager->persist($raceInfo);
        $this->entityManager->flush();
    }

    /** race_infoのデータを物理削除する **/
    public function deleteRaceInfo($id)
    {
        $raceInfoRepository = $this->entityManager->getRepository(RaceInfo::class);
        $raceInfo = $raceInfoRepository->find($id);

        if (!empty($raceInfo)) {
            $this->entityManager->remove($raceInfo);
            $this->entityManager->flush();
        }
    }

    /** createやupdate用にデータをセットする **/
    private function setRaceInfo($raceInfo, $data)
    {
        $raceInfo->setRaceDate($this->getValue($data, 'race_date', 'raceDate'));
        $raceInfo->setJyoCd($this->getValue($data, 'jyo_cd', 'jyoCd'));
        $raceInfo->setRaceNum($this->getValue($data, 'race_num', 'raceNum'));
        $raceInfo->setRaceName($this->getValue($data, 'race_name', 'raceName'));
        $raceInfo->setEntryHorceCount($this->getValue($data, 'entry_horce_count', 'entryHorceCount'));
        $raceInfo->setCourseType($this->getValue($data, 'course_type', 'courseType'));
        $raceInfo->setDistance($this->getValue($data, 'distance', 'distance'));
        $raceInfo->setRotation($this->getValue($data, 'rotation', 'rotation'));
        $raceInfo->setWeather($this->getValue($data, 'weather', 'weather'));
        $raceInfo->setBabaState($this->getValue($data, 'baba_state', 'babaState'));

        return $raceInfo;
    }

}