<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repositories\RaceScheduleRepository")]
#[ORM\Table(name: "race_schedule")]
class RaceSchedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "date", name: "race_date")]
    private \DateTime $raceDate;

    #[ORM\Column(type: "string", name: "jyo_cd")]
    private string $jyoCd;

    public function getId(): int
    {
        return $this->id;
    }

    public function getRaceDate(): \DateTime
    {
        return $this->raceDate;
    }

    public function setRaceDate(\DateTime $raceDate): void
    {
        $this->raceDate = $raceDate;
    }

    public function getJyoCd(): string
    {
        return $this->jyoCd;
    }

    public function setjyoCd(string $jyoCd): void
    {
        $this->jyoCd = $jyoCd;
    }
}