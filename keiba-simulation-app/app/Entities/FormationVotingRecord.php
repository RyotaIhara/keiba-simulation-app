<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repositories\FormationVotingRecordRepository")]
#[ORM\Table(name: "formation_voting_record")]
class FormationVotingRecord
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

    #[ORM\Column(type: "string", length: 50, name: "voting_uma_ban_1", nullable: true)]
    private ?string $votingUmaBan1 = null;

    #[ORM\Column(type: "string", length: 50, name: "voting_uma_ban_2", nullable: true)]
    private ?string $votingUmaBan2 = null;

    #[ORM\Column(type: "string", length: 50, name: "voting_uma_ban_3", nullable: true)]
    private ?string $votingUmaBan3 = null;

    #[ORM\Column(type: "integer", name: "voting_amount_formaation")]
    private int $votingAmountFormation;

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

    public function getVotingUmaBan1(): ?string
    {
        return $this->votingUmaBan1;
    }

    public function setVotingUmaBan1(?string $votingUmaBan1): void
    {
        $this->votingUmaBan1 = $votingUmaBan1;
    }

    public function getVotingUmaBan2(): ?string
    {
        return $this->votingUmaBan2;
    }

    public function setVotingUmaBan2(?string $votingUmaBan2): void
    {
        $this->votingUmaBan2 = $votingUmaBan2;
    }

    public function getVotingUmaBan3(): ?string
    {
        return $this->votingUmaBan3;
    }

    public function setVotingUmaBan3(?string $votingUmaBan3): void
    {
        $this->votingUmaBan3 = $votingUmaBan3;
    }

    public function getVotingAmountFormation(): int
    {
        return $this->votingAmountFormation;
    }

    public function setVotingAmountFormation(int $votingAmountFormation): void
    {
        $this->votingAmountFormation = $votingAmountFormation;
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
