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

    #[ORM\ManyToOne(targetEntity: "App\Entities\BettingTypeMst")]
    #[ORM\JoinColumn(name: "betting_type_mst_id", referencedColumnName: "id", nullable: false)]
    private BettingTypeMst $bettingTypeMst;

    #[ORM\Column(type: "integer", length: 4, name: "pattern")]
    private int $pattern;

    #[ORM\Column(type: "string", length: 11, name: "result_uma_ban")]
    private string $resultUmaBan;

    #[ORM\Column(type: "integer", name: "refund_amount")]
    private int $refundAmount;

    #[ORM\Column(type: "datetime", name: "created_at", nullable: true)]
    private ?\DateTime $createdAt;

    #[ORM\Column(type: "datetime", name: "updated_at", nullable: true)]
    private ?\DateTime $updatedAt;

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

    public function getBettingTypeMst(): BettingTypeMst
    {
        return $this->bettingTypeMst;
    }

    public function setBettingTypeMst(BettingTypeMst $bettingTypeMst): void
    {
        $this->bettingTypeMst = $bettingTypeMst;
    }

    public function getPattern(): int
    {
        return $this->pattern;
    }

    public function setPattern(int $pattern): void
    {
        $this->pattern = $pattern;
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

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
