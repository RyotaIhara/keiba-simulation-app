<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repositories\NagashiVotingRecordRepository")]
#[ORM\Table(name: "nagashi_voting_record")]
class NagashiVotingRecord
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\ManyToOne(targetEntity: "App\Entities\User")]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id", nullable: false)]
    private User $user;

    #[ORM\ManyToOne(targetEntity: "App\Entities\RaceInfo")]
    #[ORM\JoinColumn(name: "race_info_id", referencedColumnName: "id", nullable: false)]
    private RaceInfo $raceInfo;

    #[ORM\ManyToOne(targetEntity: "App\Entities\HowToBuyMst")]
    #[ORM\JoinColumn(name: "how_to_buy_mst_id", referencedColumnName: "id", nullable: false)]
    private HowToBuyMst $howToBuyMst;

    #[ORM\Column(type: "integer", name: "shaft_pattern")]
    private int $shaftPattern;

    #[ORM\Column(type: "string", length: 50, name: "shaft")]
    private string $shaft;

    #[ORM\Column(type: "string", length: 50, name: "partner")]
    private string $partner;

    #[ORM\Column(type: "integer", name: "voting_amount_nagashi")]
    private int $votingAmountNagashi;

    #[ORM\Column(type: "datetime", name: "created_at", nullable: true)]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(type: "datetime", name: "updated_at", nullable: true)]
    private ?\DateTime $updatedAt = null;

    // Getter and Setter methods
    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): User 
    {
        return $this->user;
    }

    public function setUser(User  $user): void
    {
        $this->user = $user;
    }

    public function getRaceInfo(): RaceInfo 
    {
        return $this->raceInfo;
    }

    public function setRaceInfo(RaceInfo  $raceInfo): void
    {
        $this->raceInfo = $raceInfo;
    }

    public function getHowToBuyMst(): HowToBuyMst
    {
        return $this->howToBuyMst;
    }

    public function setHowToBuyMst(HowToBuyMst $howToBuyMst): void
    {
        $this->howToBuyMst = $howToBuyMst;
    }

    public function getShaftPattern(): int
    {
        return $this->shaftPattern;
    }

    public function setShaftPattern(int $shaftPattern): void
    {
        $this->shaftPattern = $shaftPattern;
    }

    public function getShaft(): string
    {
        return $this->shaft;
    }

    public function setShaft(string $shaft): void
    {
        $this->shaft = $shaft;
    }

    public function getPartner(): string
    {
        return $this->partner;
    }

    public function setPartner(string $partner): void
    {
        $this->partner = $partner;
    }

    public function getVotingAmountNagashi(): int
    {
        return $this->votingAmountNagashi;
    }

    public function setVotingAmountNagashi(int $votingAmountNagashi): void
    {
        $this->votingAmountNagashi = $votingAmountNagashi;
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
