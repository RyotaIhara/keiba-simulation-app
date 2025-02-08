<?php

namespace App\Services\Crud;

use App\Entities\NetkeibaExpectedHorse;
use App\Services\Crud\CrudBase;

class NetkeibaExpectedHorseService extends CrudBase
{
    /** netkeiba_expected_horseテーブルのデータ一覧を取得する **/
    public function getAllNetkeibaExpectedHorses()
    {
        return $this->entityManager->getRepository(NetkeibaExpectedHorse::class)->findAll();
    }

    /** IDからnetkeiba_expected_horseテーブルのデータを取得する **/
    public function getNetkeibaExpectedHorse($id)
    {
        return $this->entityManager->getRepository(NetkeibaExpectedHorse::class)->find($id);
    }

    /** ユニークなカラムを指定してデータを1つ取得する **/
    public function getNetkeibaExpectedHorseByUniqueColumn($whereParams) {
        return $this->entityManager->getRepository(NetkeibaExpectedHorse::class)->findOneBy($whereParams);
    }

    /** netkeiba_expected_horseにデータを作成する **/
    public function createNetkeibaExpectedHorse(array $data)
    {
        $netkeibaExpectedHorse = new NetkeibaExpectedHorse();

        $netkeibaExpectedHorse = $this->setNetkeibaExpectedHorse($netkeibaExpectedHorse, $data);

        $this->entityManager->persist($netkeibaExpectedHorse);
        $this->entityManager->flush();

    }

    /** netkeiba_expected_horseのデータを更新する **/
    public function updateNetkeibaExpectedHorse($id, array $data)
    {
        $netkeibaExpectedHorseRepository = $this->entityManager->getRepository(NetkeibaExpectedHorse::class);
        $netkeibaExpectedHorse = $netkeibaExpectedHorseRepository->find($id);

        $netkeibaExpectedHorse = $this->setNetkeibaExpectedHorse($netkeibaExpectedHorse, $data);

        $this->entityManager->persist($netkeibaExpectedHorse);
        $this->entityManager->flush();
    }

    /** netkeiba_expected_horseのデータを削除する **/
    public function deleteNetkeibaExpectedHorse($id)
    {
        $netkeibaExpectedHorseRepository = $this->entityManager->getRepository(NetkeibaExpectedHorse::class);
        $netkeibaExpectedHorse = $netkeibaExpectedHorseRepository->find($id);

        if (!empty($netkeibaExpectedHorse)) {
            $this->entityManager->remove($netkeibaExpectedHorse);
            $this->entityManager->flush();
        }
    }

    /** createやupdate用にデータをセットする **/
    private function setNetkeibaExpectedHorse($netkeibaExpectedHorse, $data)
    {
        $netkeibaExpectedHorse->setRaceInfo($this->getValue($data, 'raceInfo', 'race_info'));
        $netkeibaExpectedHorse->setUmaBan($this->getValue($data, 'umaBan', 'uma_ban'));
        $netkeibaExpectedHorse->setFeatureDatas($this->getValue($data, 'featureDatas', 'feature_datas'));

        return $netkeibaExpectedHorse;
    }

}