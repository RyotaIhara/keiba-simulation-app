<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repositories\NetkeibaExpectedHorseRepository")]
#[ORM\Table(name: "netkeiba_expected_horse")]
class NetkeibaExpectedHorse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\ManyToOne(targetEntity: "App\Entities\RaceInfo")]
    #[ORM\JoinColumn(name: "race_info_id", referencedColumnName: "id", nullable: false)]
    private RaceInfo $raceInfo;

    #[ORM\Column(type: "integer", length: 4, name: "uma_ban", nullable: true)]
    private ?int $umaBan = null;


    #[ORM\Column(type: "string", name: "feature_datas")]
    private ?string $featureDatas = '';

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

    public function getUmaBan(): ?int
    {
        return $this->umaBan;
    }

    public function setUmaBan(?int $umaBan): void
    {
        $this->umaBan = $umaBan;
    }

    public function getFeatureDatas(): string
    {
        return $this->featureDatas;
    }

    public function setFeatureDatas(string $featureDatas): void
    {
        $this->featureDatas = $featureDatas;
    }
}
