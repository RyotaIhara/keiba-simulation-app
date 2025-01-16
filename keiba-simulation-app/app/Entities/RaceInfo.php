<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repositories\RaceInfoRepository")]
#[ORM\Table(name: "race_info")]
class RaceInfo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "date", name: "race_date", nullable: true)]
    private ?\DateTime $raceDate = null;

    #[ORM\Column(type: "string", name: "jyo_cd", nullable: true)]
    private ?string $jyoCd = null;

    #[ORM\Column(type: "integer", name: "race_num", nullable: true)]
    private ?int $raceNum = null;

    #[ORM\Column(type: "text", name: "race_name", nullable: true)]
    private ?string $raceName = null;

    #[ORM\Column(type: "integer", name: "entry_horce_count", nullable: true)]
    private ?int $entryHorceCount = null;

    #[ORM\Column(type: "string", name: "course_type", nullable: true)]
    private ?string $courseType = null;

    #[ORM\Column(type: "integer", name: "distance", nullable: true)]
    private ?int $distance = null;

    #[ORM\Column(type: "string", length: 11, name: "rotation", nullable: true)]
    private ?string $rotation = null;

    #[ORM\Column(type: "string", length: 11, name: "weather", nullable: true)]
    private ?string $weather = null;

    #[ORM\Column(type: "string", length: 11, name: "baba_state", nullable: true)]
    private ?string $babaState = null;

    // サイト表示用のカラム
    private ?string $racecourseName = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getRaceDate(): ?\DateTime
    {
        return $this->raceDate;
    }

    public function setRaceDate(?\DateTime $raceDate): void
    {
        $this->raceDate = $raceDate;
    }

    public function getJyoCd(): ?string
    {
        return $this->jyoCd;
    }

    public function setJyoCd(?string $jyoCd): void
    {
        $this->jyoCd = $jyoCd;
    }

    public function getRaceNum(): ?int
    {
        return $this->raceNum;
    }

    public function setRaceNum(?int $raceNum): void
    {
        $this->raceNum = $raceNum;
    }

    public function getRaceName(): ?string
    {
        return $this->raceName;
    }

    public function setRaceName(?string $raceName): void
    {
        $this->raceName = $raceName;
    }

    public function getEntryHorceCount(): ?int
    {
        return $this->entryHorceCount;
    }

    public function setEntryHorceCount(?int $entryHorceCount): void
    {
        $this->entryHorceCount = $entryHorceCount;
    }

    public function getCourseType(): ?string
    {
        return $this->courseType;
    }

    public function setCourseType(?string $courseType): void
    {
        $this->courseType = $courseType;
    }

    public function getDistance(): ?int
    {
        return $this->distance;
    }

    public function setDistance(?int $distance): void
    {
        $this->distance = $distance;
    }

    public function getRotation(): ?string
    {
        return $this->rotation;
    }

    public function setRotation(?string $rotation): void
    {
        $this->rotation = $rotation;
    }

    public function getWeather(): ?string
    {
        return $this->weather;
    }

    public function setWeather(?string $weather): void
    {
        $this->weather = $weather;
    }

    public function getBabaState(): ?string
    {
        return $this->babaState;
    }

    public function setBabaState(?string $babaState): void
    {
        $this->babaState = $babaState;
    }

    public function getRacecourseName(): ?string
    {
        //return $this->racecourseName;
        return '大井競馬場';
    }

    public function setRacecourseName(?string $racecourseName): void
    {
        $this->racecourseName = $racecourseName;
    }
}
