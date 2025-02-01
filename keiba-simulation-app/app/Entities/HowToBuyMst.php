<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repositories\HowToBuyMstRepository")]
#[ORM\Table(name: "how_to_buy_mst")]
class HowToBuyMst
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 11, name: "how_to_buy_code")]
    private string $howToBuyCode;

    #[ORM\Column(type: "string", length: 11, name: "how_to_buy_name")]
    private string $howToBuyName;

    public function getId(): int
    {
        return $this->id;
    }

    public function getHowToBuyCode(): string
    {
        return $this->howToBuyCode;
    }

    public function setHowToBuyCode(string $howToBuyCode): void
    {
        $this->howToBuyCode = $howToBuyCode;
    }

    public function getHowToBuyName(): string
    {
        return $this->howToBuyName;
    }

    public function setHowToBuyName(string $howToBuyName): void
    {
        $this->howToBuyName = $howToBuyName;
    }
}
