<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repositories\RaceCardRepository")]
#[ORM\Table(name: "race_card")]
class RaceCard
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\ManyToOne(targetEntity: "App\Entities\RaceInfo")]
    #[ORM\JoinColumn(name: "race_info_id", referencedColumnName: "id", nullable: false)]
    private RaceInfo $raceInfo;

    #[ORM\Column(type: "integer", name: "waku_ban", nullable: true)]
    private ?int $wakuBan = null;

    #[ORM\Column(type: "integer", name: "uma_ban", nullable: true)]
    private ?int $umaBan = null;

    #[ORM\Column(type: "text", name: "horse_name", nullable: true)]
    private ?string $horseName = null;

    #[ORM\Column(type: "string", length: 8, name: "age", nullable: true)]
    private ?string $age = null;

    #[ORM\Column(type: "float", name: "weight", nullable: true)]
    private ?float $weight = null;

    #[ORM\Column(type: "text", name: "jockey_name", nullable: true)]
    private ?string $jockeyName = null;

    #[ORM\Column(type: "integer", name: "favourite", nullable: true)]
    private ?int $favourite = null;

    #[ORM\Column(type: "float", name: "win_odds", nullable: true)]
    private ?float $winOdds = null;

    #[ORM\Column(type: "text", name: "stable", nullable: true)]
    private ?string $stable = null;

    #[ORM\Column(type: "float", name: "weight_gain_loss", nullable: true)]
    private ?float $weightGainLoss = null;

    #[ORM\Column(type: "boolean", name: "is_cancel", options: ["default" => 0])]
    private bool $isCancel = false;

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

    public function getWakuBan(): ?int
    {
        return $this->wakuBan;
    }

    public function setWakuBan(?int $wakuBan): void
    {
        $this->wakuBan = $wakuBan;
    }

    public function getUmaBan(): ?int
    {
        return $this->umaBan;
    }

    public function setUmaBan(?int $umaBan): void
    {
        $this->umaBan = $umaBan;
    }

    public function getHorseName(): ?string
    {
        return $this->horseName;
    }

    public function setHorseName(?string $horseName): void
    {
        $this->horseName = $horseName;
    }

    public function getAge(): ?string
    {
        return $this->age;
    }

    public function setAge(?string $age): void
    {
        $this->age = $age;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(?float $weight): void
    {
        $this->weight = $weight;
    }

    public function getJockeyName(): ?string
    {
        return $this->jockeyName;
    }

    public function setJockeyName(?string $jockeyName): void
    {
        $this->jockeyName = $jockeyName;
    }

    public function getFavourite(): ?int
    {
        return $this->favourite;
    }

    public function setFavourite(?int $favourite): void
    {
        $this->favourite = $favourite;
    }

    public function getWinOdds(): ?float
    {
        return $this->winOdds;
    }

    public function setWinOdds(?float $winOdds): void
    {
        $this->winOdds = $winOdds;
    }

    public function getStable(): ?string
    {
        return $this->stable;
    }

    public function setStable(?string $stable): void
    {
        $this->stable = $stable;
    }

    public function getWeightGainLoss(): ?float
    {
        return $this->weightGainLoss;
    }

    public function setWeightGainLoss(?float $weightGainLoss): void
    {
        $this->weightGainLoss = $weightGainLoss;
    }

    public function isCancel(): bool
    {
        return $this->isCancel;
    }

    public function setIsCancel(bool $isCancel): void
    {
        $this->isCancel = $isCancel;
    }
}
