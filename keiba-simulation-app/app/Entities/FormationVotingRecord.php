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

    #[ORM\ManyToOne(targetEntity: "App\Entities\VotingRecord")]
    #[ORM\JoinColumn(name: "voting_record_id", referencedColumnName: "id", nullable: false)]
    private VotingRecord $votingRecord;

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

    public function getVotingRecord(): VotingRecord 
    {
        return $this->votingRecord;
    }

    public function setVotingRecord(VotingRecord  $votingRecord): void
    {
        $this->votingRecord = $votingRecord;
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
