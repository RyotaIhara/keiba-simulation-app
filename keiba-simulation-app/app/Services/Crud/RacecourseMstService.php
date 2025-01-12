<?php

namespace App\Services\Crud;

use App\Entities\RacecourseMst;
use App\Services\Crud\CrudBase;

class RacecourseMstService extends CrudBase
{
    /** racecourse_mstテーブルのデータ一覧を取得する **/
    public function getAllRacecourseMsts()
    {
        return $this->entityManager->getRepository(RacecourseMst::class)->findAll();
    }

    /** IDからracecourse_mstテーブルのデータを取得する **/
    public function getRacecourseMst($id)
    {
        return $this->entityManager->getRepository(RacecourseMst::class)->find($id);
    }

    /** ユニークなカラムを指定してデータを1つ取得する **/
    public function getRacecourseMstByUniqueColumn($whereParams) {
        foreach ($whereParams as $item) {

        }
        return $this->entityManager->getRepository(RacecourseMst::class)->findOneBy( $whereParams);
    }

    /** racecourse_mstにデータを作成する **/
    public function createRacecourseMst(array $data)
    {
        $racecourseMst = new RacecourseMst();

        $racecourseMst->setJyoCd($data['jyo_cd']);
        $racecourseMst->setRacecourseName($data['racecourse_name']);

        $this->entityManager->persist($racecourseMst);
        $this->entityManager->flush();

    }

    /** racecourse_mstのデータを更新する **/
    public function updateRacecourseMst($id, array $data)
    {
        $racecourseMstRepository = $this->entityManager->getRepository(RacecourseMst::class);
        $racecourseMst = $racecourseMstRepository->find($id);

        $racecourseMst->setJyoCd($data['jyo_cd']);
        $racecourseMst->setRacecourseName($data['racecourse_name']);

        $this->entityManager->persist($racecourseMst);
        $this->entityManager->flush();
    }

    /** racecourse_mstのデータを削除する **/
    public function deleteRacecourseMst($id)
    {
        $racecourseMstRepository = $this->entityManager->getRepository(RacecourseMst::class);
        $racecourseMst = $racecourseMstRepository->find($id);

        if (!empty($racecourseMst)) {
            $this->entityManager->remove($racecourseMst);
            $this->entityManager->flush();
        }
    }

}