<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repositories\RacecourseMstRepository")]
#[ORM\Table(name: "racecourse_mst")]
class RacecourseMst
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", name: "jyo_cd")]
    private string $jyoCd;

    #[ORM\Column(type: "string", name: "racecourse_name")]
    private string $racecourseName;

    public function getId(): int
    {
        return $this->id;
    }

    public function getJyoCd(): string
    {
        return $this->jyoCd;
    }

    public function setjyoCd(string $jyoCd): void
    {
        $this->jyoCd = $jyoCd;
    }

    public function getRacecourseName(): string
    {
        return $this->racecourseName;
    }

    public function setRacecourseName(string $racecourseName): void
    {
        $this->racecourseName = $racecourseName;
    }
}
