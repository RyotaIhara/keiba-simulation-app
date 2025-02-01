<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repositories\VotingRecordDetailRepository")]
#[ORM\Table(name: "voting_record_detail")]
class VotingRecordDetail
{
    const DEFALT_REFUND_AMOUNT = 0;
    const DEFALT_HIT_STATUS = 0;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\ManyToOne(targetEntity: "App\Entities\VotingRecord")]
    #[ORM\JoinColumn(name: "voting_record_id", referencedColumnName: "id", nullable: false)]
    private VotingRecord $votingRecord;

    #[ORM\Column(type: "string", length: 50, name: "voting_uma_ban")]
    private ?string $votingUmaBan = null;

    #[ORM\Column(type: "integer", name: "voting_amount")]
    private ?int $votingAmount = null;

    #[ORM\Column(type: "integer", name: "refund_amount")]
    private int $refundAmount = self::DEFALT_REFUND_AMOUNT;

    /** （0：未確定、1：的中、2：外れ） **/
    #[ORM\Column(type: "integer", name: "hit_status")]
    private int $hitStatus = self::DEFALT_HIT_STATUS;

    #[ORM\Column(type: "datetime", name: "created_at", nullable: true)]
    private ?\DateTime $createdAt;

    #[ORM\Column(type: "datetime", name: "updated_at", nullable: true)]
    private ?\DateTime $updatedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function getVotingRecord(): VotingRecord 
    {
        return $this->votingRecord;
    }

    public function setVotingRecord(?VotingRecord  $votingRecord): void
    {
        $this->votingRecord = $votingRecord;
    }

    public function getVotingUmaBan(): ?string
    {
        return $this->votingUmaBan;
    }

    public function setVotingUmaBan(string $votingUmaBan): void
    {
        $this->votingUmaBan = $votingUmaBan;
    }

    public function getVotingAmount(): ?int
    {
        return $this->votingAmount;
    }

    public function setVotingAmount(int $votingAmount): void
    {
        $this->votingAmount = $votingAmount;
    }

    public function getRefundAmount(): ?int
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
