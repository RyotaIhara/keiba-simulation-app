<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repositories\RaceRefundAmountRepository")]
#[ORM\Table(name: "race_refund_amount")]
class RaceRefundAmount
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\ManyToOne(targetEntity: "App\Entities\RaceInfo")]
    #[ORM\JoinColumn(name: "race_info_id", referencedColumnName: "id", nullable: false)]
    private RaceInfo $raceInfo;

    #[ORM\Column(type: "integer", name: "how_to_buy_mst_id")]
    private int $howToBuyMstId;

    #[ORM\Column(type: "string", length: 11, name: "result_uma_ban")]
    private string $resultUmaBan;

    #[ORM\Column(type: "integer", name: "refund_amount")]
    private int $refundAmount;

    public function getId(): int
    {
        return $this->id;
    }

    public function getRaceInfo(): RaceInfo
    {
        return $this->raceInfo;
    }

    public function setRaceInfo(RaceInfo $raceInfo): void
    {
        $this->raceInfo = $raceInfo;
    }

    public function getHowToBuyMstId(): int
    {
        return $this->howToBuyMstId;
    }

    public function setHowToBuyMstId(int $howToBuyMstId): void
    {
        $this->howToBuyMstId = $howToBuyMstId;
    }

    public function getResultUmaBan(): string
    {
        return $this->resultUmaBan;
    }

    public function setResultUmaBan(string $resultUmaBan): void
    {
        $this->resultUmaBan = $resultUmaBan;
    }

    public function getRefundAmount(): int
    {
        return $this->refundAmount;
    }

    public function setRefundAmount(int $refundAmount): void
    {
        $this->refundAmount = $refundAmount;
    }
}
