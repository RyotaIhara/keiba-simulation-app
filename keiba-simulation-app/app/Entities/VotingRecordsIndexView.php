<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repositories\VotingRecordsIndexViewRepository")]
#[ORM\Table(name: "voting_records_index_view")]
class VotingRecordsIndexView
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer", name: "voting_record_id")]
    private int $votingRecordId;

    #[ORM\Column(type: "integer", name: "race_info_id")]
    private int $raceInfoId;

    #[ORM\Column(type: "integer", name: "how_to_buy_mst_id")]
    private int $howToBuyMstId;

    #[ORM\Column(type: "string", name: "voting_uma_ban")]
    private string $votingUmaBan;

    #[ORM\Column(type: "integer", name: "voting_amount")]
    private int $votingAmount;

    #[ORM\Column(type: "integer", name: "refund_amount")]
    private int $refundAmount;

    #[ORM\Column(type: "integer", name: "hit_status")]
    private int $hitStatus;

    #[ORM\Column(type: "string", name: "racecourse_name")]
    private string $racecourseName;

    #[ORM\Column(type: "datetime", name: "race_date")]
    private \DateTime $raceDate;

    #[ORM\Column(type: "integer", name: "race_num")]
    private int $raceNum;

    #[ORM\Column(type: "string", name: "race_name")]
    private string $raceName;

    #[ORM\Column(type: "integer", name: "entry_horce_count")]
    private int $entryHorseCount;

    #[ORM\Column(type: "string", name: "jyo_cd")]
    private string $jyoCd;

    #[ORM\Column(type: "string", name: "how_to_buy_name")]
    private string $howToBuyName;

    // Getters and Setters

    public function getVotingRecordId(): int
    {
        return $this->votingRecordId;
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

    public function getRacecourseName(): string
    {
        return $this->racecourseName;
    }

    public function setRacecourseName(string $racecourseName): void
    {
        $this->racecourseName = $racecourseName;
    }

    public function getRaceDate(): \DateTime
    {
        return $this->raceDate;
    }

    public function setRaceDate(\DateTime $raceDate): void
    {
        $this->raceDate = $raceDate;
    }

    public function getRaceNum(): int
    {
        return $this->raceNum;
    }

    public function setRaceNum(int $raceNum): void
    {
        $this->raceNum = $raceNum;
    }

    public function getRaceName(): string
    {
        return $this->raceName;
    }

    public function setRaceName(string $raceName): void
    {
        $this->raceName = $raceName;
    }

    public function getEntryHorseCount(): int
    {
        return $this->entryHorseCount;
    }

    public function setEntryHorseCount(int $entryHorseCount): void
    {
        $this->entryHorseCount = $entryHorseCount;
    }

    public function getJyoCd(): string
    {
        return $this->jyoCd;
    }

    public function setJyoCd(string $jyoCd): void
    {
        $this->jyoCd = $jyoCd;
    }

    public function getHowToBuyName(): string
    {
        return $this->howToBuyName;
    }

    public function setHowToBuyName(string $howToBuyName): void
    {
        $this->howToBuyName = $howToBuyName;
    }
}
