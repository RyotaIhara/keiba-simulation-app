<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repositories\VotingRecordRepository")]
#[ORM\Table(name: "voting_record")]
class VotingRecord
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "integer", name: "race_info_id")]
    private int $raceInfoId;

    #[ORM\Column(type: "integer", name: "how_to_buy_mst_id")]
    private int $howToBuyMstId;

    #[ORM\Column(type: "string", length: 11, name: "voting_uma_ban")]
    private string $votingUmaBan;

    #[ORM\Column(type: "integer", name: "voting_amount")]
    private int $votingAmount;

    #[ORM\Column(type: "integer", name: "refund_amount")]
    private int $refundAmount;

    /** （0：未確定、1：的中、2：外れ） **/
    #[ORM\Column(type: "integer", name: "hit_status")]
    private int $hitStatus;

    public function getId(): int
    {
        return $this->id;
    }

    public function getRaceInfoId(): int
    {
        return $this->raceInfoId;
    }

    public function setRaceInfoId(int $raceInfoId): void
    {
        $this->raceInfoId = $raceInfoId;
    }

    public function getHowToBuyMstId(): int
    {
        return $this->howToBuyMstId;
    }

    public function setHowToBuyMstId(int $howToBuyMstId): void
    {
        $this->howToBuyMstId = $howToBuyMstId;
    }

    public function getVotingUmaBan(): string
    {
        return $this->votingUmaBan;
    }

    public function setVotingUmaBan(string $votingUmaBan): void
    {
        $this->votingUmaBan = $votingUmaBan;
    }

    public function getVotingAmount(): int
    {
        return $this->votingAmount;
    }

    public function setVotingAmount(int $votingAmount): void
    {
        $this->votingAmount = $votingAmount;
    }

    public function getRefundAmount(): int
    {
        return $this->refundAmount;
    }

    public function setRefundAmount(int $refundAmount): void
    {
        $this->refundAmount = $refundAmount;
    }

    public function getHitStatus(): int
    {
        return $this->hitStatus;
    }

    public function setHitStatus(int $hitStatus): void
    {
        $this->hitStatus = $hitStatus;
    }
}
