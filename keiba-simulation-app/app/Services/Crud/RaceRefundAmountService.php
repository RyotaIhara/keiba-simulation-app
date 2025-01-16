<?php

namespace App\Services\Crud;

use Doctrine\ORM\EntityManagerInterface;
use App\Entities\RaceRefundAmount;
use App\Services\Crud\CrudBase;

class RaceRefundAmountService extends CrudBase
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
    }

    /** race_refund_amountテーブルのデータ一覧を取得する **/
    public function getAllRaceRefundAmounts()
    {
        return $this->entityManager->getRepository(RaceRefundAmount::class)->findAll();
    }

    /** IDからrace_refund_amountテーブルのデータを取得する **/
    public function getRaceRefundAmount($id)
    {
        return $this->entityManager->getRepository(RaceRefundAmount::class)->find($id);
    }

    /** ユニークなカラムを指定してデータを1つ取得する **/
    public function getRaceRefundAmountByUniqueColumn($whereParams) {
        return $this->entityManager->getRepository(RaceRefundAmount::class)->findOneBy($whereParams);
    }

    /** race_refund_amountにデータを作成する **/
    public function createRaceRefundAmount(array $data)
    {
        $raceRefundAmount = new RaceRefundAmount();

        $raceRefundAmount = $this->setRaceRefundAmount($raceRefundAmount, $data);

        $this->entityManager->persist($raceRefundAmount);
        $this->entityManager->flush();

    }

    /** race_refund_amountのデータを更新する **/
    public function updateRaceRefundAmount($id, array $data)
    {
        $raceRefundAmountRepository = $this->entityManager->getRepository(RaceRefundAmount::class);
        $raceRefundAmount = $raceRefundAmountRepository->find($id);

        $raceRefundAmount = $this->setRaceRefundAmount($raceRefundAmount, $data);

        $this->entityManager->persist($raceRefundAmount);
        $this->entityManager->flush();
    }

    /** race_refund_amountのデータを物理削除する **/
    public function deleteRaceRefundAmount($id)
    {
        $raceRefundAmountRepository = $this->entityManager->getRepository(RaceRefundAmount::class);
        $raceRefundAmount = $raceRefundAmountRepository->find($id);

        if (!empty($raceRefundAmount)) {
            $this->entityManager->remove($raceRefundAmount);
            $this->entityManager->flush();
        }
    }

    /** createやupdate用にデータをセットする **/
    private function setRaceRefundAmount($raceRefundAmount, $data)
    {
        $raceRefundAmount->setRaceInfoId($this->getValue($data, 'race_info_id', 'raceInfoId'));
        $raceRefundAmount->setHowToBuyMstId($this->getValue($data, 'how_to_buy_mst_id', 'howToBuyMstId'));
        $raceRefundAmount->setResultUmaBan($this->getValue($data, 'result_uma_ban', 'resultUmaBan '));
        $raceRefundAmount->setRefundAmount($this->getValue($data, 'refund_amount', 'refundAmount'));
        return $raceRefundAmount;
    }

}