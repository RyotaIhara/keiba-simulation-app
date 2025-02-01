<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repositories\BoxVotingRecordRepository")]
#[ORM\Table(name: "box_voting_record")]
class BoxVotingRecord
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\ManyToOne(targetEntity: "App\Entities\VotingRecord")]
    #[ORM\JoinColumn(name: "voting_record_id", referencedColumnName: "id", nullable: false)]
    private VotingRecord $votingRecord;

    #[ORM\Column(type: "string", length: 50, name: "voting_uma_ban_box")]
    private string $votingUmaBanBox;

    #[ORM\Column(type: "integer", name: "voting_amount_box")]
    private int $votingAmountBox;

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

    public function getVotingUmaBanBox(): string
    {
        return $this->votingUmaBanBox;
    }

    public function setVotingUmaBanBox(string $votingUmaBanBox): void
    {
        $this->votingUmaBanBox = $votingUmaBanBox;
    }

    public function getVotingAmountBox(): int
    {
        return $this->votingAmountBox;
    }

    public function setVotingAmountBox(int $votingAmountBox): void
    {
        $this->votingAmountBox = $votingAmountBox;
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
