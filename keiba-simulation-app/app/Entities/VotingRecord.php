<?php

namespace App\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repositories\VotingRecordRepository")]
#[ORM\Table(name: "voting_record")]
class VotingRecord
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
    private ?RaceInfo $raceInfo = null;

    #[ORM\ManyToOne(targetEntity: "App\Entities\HowToBuyMst")]
    #[ORM\JoinColumn(name: "how_to_buy_mst_id", referencedColumnName: "id", nullable: false)]
    private ?HowToBuyMst $howToBuyMst = null;
    
    #[ORM\ManyToOne(targetEntity: "App\Entities\BettingTypeMst")]
    #[ORM\JoinColumn(name: "betting_type_mst_id", referencedColumnName: "id", nullable: false)]
    private ?BettingTypeMst $bettingTypeMst = null;

    #[ORM\OneToMany(mappedBy: "votingRecord", targetEntity: VotingRecordDetail::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    private Collection $votingRecordDetails;

    #[ORM\Column(type: "datetime", name: "created_at", nullable: true)]
    private ?\DateTime $createdAt;

    #[ORM\Column(type: "datetime", name: "updated_at", nullable: true)]
    private ?\DateTime $updatedAt;

    public function __construct()
    {
        $this->votingRecordDetails = new ArrayCollection();
    }

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

    public function getBettingTypeMst(): ?BettingTypeMst
    {
        return $this->bettingTypeMst;
    }

    public function setBettingTypeMst(BettingTypeMst $bettingTypeMst): void
    {
        $this->bettingTypeMst = $bettingTypeMst;
    }

    /**
     * 関連する VotingRecordDetail を取得する
     */
    public function getVotingRecordDetails(): Collection
    {
        return $this->votingRecordDetails;
    }

    /**
     * 関連する VotingRecordDetail を削除する
     */
    public function removeAllVotingRecordDetails(): void
    {
        $this->votingRecordDetails->clear();
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
