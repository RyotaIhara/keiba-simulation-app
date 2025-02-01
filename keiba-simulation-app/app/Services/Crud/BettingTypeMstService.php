<?php

namespace App\Services\Crud;

use App\Entities\BettingTypeMst;
use App\Services\Crud\CrudBase;

class BettingTypeMstService extends CrudBase
{
    /** betting_type_mstテーブルのデータ一覧を取得する **/
    public function getAllBettingTypeMsts()
    {
        return $this->entityManager->getRepository(BettingTypeMst::class)->findAll();
    }

    /** IDからbetting_type_mstテーブルのデータを取得する **/
    public function getBettingTypeMst($id)
    {
        return $this->entityManager->getRepository(BettingTypeMst::class)->find($id);
    }

    /** ユニークなカラムを指定してデータを1つ取得する **/
    public function getBettingTypeMstByUniqueColumn($whereParams) {
        return $this->entityManager->getRepository(BettingTypeMst::class)->findOneBy($whereParams);
    }

    /** betting_type_mstにデータを作成する **/
    public function createBettingTypeMst(array $data)
    {
        $bettingTypeMst = new BettingTypeMst();

        $bettingTypeMst = $this->setBettingTypeMst($bettingTypeMst, $data);

        $this->entityManager->persist($bettingTypeMst);
        $this->entityManager->flush();

        return $bettingTypeMst;
    }

    /** betting_type_mstのデータを更新する **/
    public function updateBettingTypeMst($id, array $data)
    {
        $bettingTypeMstRepository = $this->entityManager->getRepository(BettingTypeMst::class);
        $bettingTypeMst = $bettingTypeMstRepository->find($id);

        $bettingTypeMst = $this->setBettingTypeMst($bettingTypeMst, $data);

        $this->entityManager->persist($bettingTypeMst);
        $this->entityManager->flush();
    }

    /** betting_type_mstのデータを削除する **/
    public function deleteBettingTypeMst($id)
    {
        $bettingTypeMstRepository = $this->entityManager->getRepository(BettingTypeMst::class);
        $bettingTypeMst = $bettingTypeMstRepository->find($id);

        if (!empty($bettingTypeMst)) {
            $this->entityManager->remove($bettingTypeMst);
            $this->entityManager->flush();
        }
    }

    /** createやupdate用にデータをセットする **/
    private function setBettingTypeMst($bettingTypeMst, $data)
    {
        $bettingTypeMst->setBettingTypeCode($this->getValue($data, 'betting_type_code', 'bettingTypeCode'));
        $bettingTypeMst->setBettingTypeName($this->getValue($data, 'betting_type_name', 'bettingTypeName'));
        $bettingTypeMst->setIsOrdered($this->getValue($data, 'is_ordered', 'isOrdered'));

        return $bettingTypeMst;
    }

}