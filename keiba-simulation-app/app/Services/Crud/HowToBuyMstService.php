<?php

namespace App\Services\Crud;

use Doctrine\ORM\EntityManagerInterface;
use App\Entities\HowToBuyMst;
use App\Services\Crud\CrudBase;

class HowToBuyMstService extends CrudBase
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
    }

    /** how_to_buy_mstテーブルのデータ一覧を取得する **/
    public function getAllHowToBuyMsts()
    {
        return $this->entityManager->getRepository(HowToBuyMst::class)->findAll();
    }

    /** IDからhow_to_buy_mstテーブルのデータを取得する **/
    public function getHowToBuyMst($id)
    {
        return $this->entityManager->getRepository(HowToBuyMst::class)->find($id);
    }

    /** ユニークなカラムを指定してデータを1つ取得する **/
    public function getHowToBuyMstByUniqueColumn($whereParams) {
        return $this->entityManager->getRepository(HowToBuyMst::class)->findOneBy($whereParams);
    }

    /** how_to_buy_mstにデータを作成する **/
    public function createHowToBuyMst(array $data)
    {
        $howToBuyMst = new HowToBuyMst();

        $howToBuyMst = $this->setHowToBuyMst($howToBuyMst, $data);

        $this->entityManager->persist($howToBuyMst);
        $this->entityManager->flush();

    }

    /** how_to_buy_mstのデータを更新する **/
    public function updateHowToBuyMst($id, array $data)
    {
        $howToBuyMstRepository = $this->entityManager->getRepository(HowToBuyMst::class);
        $howToBuyMst = $howToBuyMstRepository->find($id);

        $howToBuyMst = $this->setHowToBuyMst($howToBuyMst, $data);

        $this->entityManager->persist($howToBuyMst);
        $this->entityManager->flush();
    }

    /** how_to_buy_mstのデータを物理削除する **/
    public function deleteHowToBuyMst($id)
    {
        $howToBuyMstRepository = $this->entityManager->getRepository(HowToBuyMst::class);
        $howToBuyMst = $howToBuyMstRepository->find($id);

        if (!empty($howToBuyMst)) {
            $this->entityManager->remove($howToBuyMst);
            $this->entityManager->flush();
        }
    }

    /** createやupdate用にデータをセットする **/
    private function setHowToBuyMst($howToBuyMst, $data)
    {
        $howToBuyMst->setHowToBuyCode($this->getValue($data, 'how_to_buy_code', 'howToBuyCode'));
        $howToBuyMst->setHowToBuyName($this->getValue($data, 'how_to_buy_name', 'howToBuyName'));
        $howToBuyMst->setIsOrdered($this->getValue($data, 'is_ordered', 'isOrdered'));

        return $howToBuyMst;
    }

}