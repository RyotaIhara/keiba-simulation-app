<?php

namespace App\Services\Crud;

use Doctrine\ORM\EntityManagerInterface;
use App\Entities\RaceCard;
use App\Services\Crud\CrudBase;
use App\Services\Crud\RaceInfoService;

class RaceCardService extends CrudBase
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
    }

    /** race_cardテーブルのデータ一覧を取得する **/
    public function getAllRaceCards()
    {
        return $this->entityManager->getRepository(RaceCard::class)->findAll();
    }

    /** IDからrace_cardテーブルのデータを取得する **/
    public function getRaceCard($id)
    {
        return $this->entityManager->getRepository(RaceCard::class)->find($id);
    }

    /** ユニークなカラムを指定してデータを1つ取得する **/
    public function getRaceCardByUniqueColumn($whereParams) {
        return $this->entityManager->getRepository(RaceCard::class)->findOneBy($whereParams);
    }

    /** race_cardにデータを作成する **/
    public function createRaceCard(array $data)
    {
        $raceCard = new RaceCard();

        $raceCard = $this->setRaceCard($raceCard, $data);

        $this->entityManager->persist($raceCard);
        $this->entityManager->flush();

    }

    /** race_cardのデータを更新する **/
    public function updateRaceCard($id, array $data)
    {
        $raceCardRepository = $this->entityManager->getRepository(RaceCard::class);
        $raceCard = $raceCardRepository->find($id);

        $raceCard = $this->setRaceCard($raceCard, $data);

        $this->entityManager->persist($raceCard);
        $this->entityManager->flush();
    }

    /** race_cardのデータを物理削除する **/
    public function deleteRaceCard($id)
    {
        $raceCardRepository = $this->entityManager->getRepository(RaceCard::class);
        $raceCard = $raceCardRepository->find($id);

        if (!empty($raceCard)) {
            $this->entityManager->remove($raceCard);
            $this->entityManager->flush();
        }
    }

    /** createやupdate用にデータをセットする **/
    private function setRaceCard($raceCard, $data)
    {
        $raceInfoService = app(RaceInfoService::class);
        $raceInfo = $raceInfoService->getRaceInfo($this->getValue($data, 'race_info_id', 'raceInfoId'));

        $raceCard->setRaceInfo($raceInfo);
        $raceCard->setWakuBan($this->getValue($data, 'waku_ban', 'wakuBan'));
        $raceCard->setUmaBan($this->getValue($data, 'uma_ban', 'umaBan'));
        $raceCard->setHorseName($this->getValue($data, 'horse_name', 'horseName'));
        $raceCard->setAge($this->getValue($data, 'age', 'age'));
        $raceCard->setWeight($this->getValue($data, 'weight', 'weight'));
        $raceCard->setJockeyName($this->getValue($data, 'jockey_name', 'jockeyName'));
        $raceCard->setFavourite($this->getValue($data, 'favourite', 'favourite'));
        $raceCard->setWinOdds($this->getValue($data, 'win_odds', 'winOdds'));
        $raceCard->setStable($this->getValue($data, 'stable', 'stable'));
        if (is_numeric($this->getValue($data, 'weight_gain_loss', 'weightGainLoss'))) {
            $raceCard->setWeightGainLoss($this->getValue($data, 'weight_gain_loss', 'weightGainLoss'));
        } 
        $raceCard->setIsCancel($this->getValue($data, 'is_cancel', 'isCancel'));

        return $raceCard;
    }

}