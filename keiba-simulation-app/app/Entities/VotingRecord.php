<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repositories\VotingRecordRepository")]
#[ORM\Table(name: "voting_record")]
class VotingRecord
{
    const DEFALT_REFUND_AMOUNT = 0;
    const DEFALT_HIT_STATUS = 0;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\ManyToOne(targetEntity: "App\Entities\User")]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id", nullable: false)]
    private User $user;

    #[ORM\ManyToOne(targetEntity: "App\Entities\RaceInfo")]
    #[ORM\JoinColumn(name: "race_info_id", referencedColumnName: "id", nullable: false)]
    private ?RaceInfo $raceInfo = null;

    #[ORM\ManyToOne(targetEntity: "App\Entities\HowToBuyMst")]
    #[ORM\JoinColumn(name: "how_to_buy_mst_id", referencedColumnName: "id", nullable: false)]
    private ?HowToBuyMst $howToBuyMst = null;

    #[ORM\Column(type: "string", length: 50, name: "voting_uma_ban")]
    private ?string $votingUmaBan = null;

    #[ORM\Column(type: "integer", name: "voting_amount")]
    private ?int $votingAmount = null;

    #[ORM\Column(type: "integer", name: "refund_amount")]
    private int $refundAmount = self::DEFALT_REFUND_AMOUNT;

    /** （0：未確定、1：的中、2：外れ） **/
    #[ORM\Column(type: "integer", name: "hit_status")]
    private int $hitStatus = self::DEFALT_HIT_STATUS;

    #[ORM\ManyToOne(targetEntity: "App\Entities\FormationVotingRecord")]
    #[ORM\JoinColumn(name: "formation_voting_record_id", referencedColumnName: "id", nullable: false)]
    private FormationVotingRecord $formationVotingRecord;

    #[ORM\ManyToOne(targetEntity: "App\Entities\BoxVotingRecord")]
    #[ORM\JoinColumn(name: "box_voting_record_id", referencedColumnName: "id", nullable: false)]
    private BoxVotingRecord $boxVotingRecord;

    #[ORM\ManyToOne(targetEntity: "App\Entities\NagashiVotingRecord")]
    #[ORM\JoinColumn(name: "nagashi_voting_record_id", referencedColumnName: "id", nullable: false)]
    private NagashiVotingRecord $nagashiVotingRecord;

    #[ORM\Column(type: "datetime", name: "created_at", nullable: true)]
    private ?\DateTime $createdAt;

    #[ORM\Column(type: "datetime", name: "updated_at", nullable: true)]
    private ?\DateTime $updatedAt;

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

    public function getRaceInfo(): ?RaceInfo 
    {
        return $this->raceInfo;
    }

    public function setRaceInfo(RaceInfo  $raceInfo): void
    {
        $this->raceInfo = $raceInfo;
    }

    public function getHowToBuyMst(): ?HowToBuyMst
    {
        return $this->howToBuyMst;
    }

    public function setHowToBuyMst(HowToBuyMst $howToBuyMst): void
    {
        $this->howToBuyMst = $howToBuyMst;
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

    public function getFormationVotingRecord(): FormationVotingRecord
    {
        return $this->formationVotingRecord;
    }

    public function setFormationVotingRecord(FormationVotingRecord $formationVotingRecord): void
    {
        $this->formationVotingRecord = $formationVotingRecord;
    }

    public function getBoxVotingRecord(): BoxVotingRecord
    {
        return $this->boxVotingRecord;
    }

    public function setBoxVotingRecord(BoxVotingRecord $boxVotingRecord): void
    {
        $this->boxVotingRecord = $boxVotingRecord;
    }

    public function getNagashiVotingRecord(): NagashiVotingRecord
    {
        return $this->nagashiVotingRecord;
    }

    public function setNagashiVotingRecord(NagashiVotingRecord $nagashiVotingRecord): void
    {
        $this->nagashiVotingRecord = $nagashiVotingRecord;
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
