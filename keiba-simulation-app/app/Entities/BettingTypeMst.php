<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repositories\BettingTypeMstRepository")]
#[ORM\Table(name: "betting_type_mst")]
class BettingTypeMst
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 11, name: "betting_type_code")]
    private string $bettingTypeCode;

    #[ORM\Column(type: "string", length: 11, name: "betting_type_name")]
    private string $bettingTypeName;

    #[ORM\Column(type: "boolean", name: "is_ordered")]
    private bool $isOrdered;

    public function getId(): int
    {
        return $this->id;
    }

    public function getBettingTypeCode(): string
    {
        return $this->bettingTypeCode;
    }

    public function setBettingTypeCode(string $bettingTypeCode): void
    {
        $this->bettingTypeCode = $bettingTypeCode;
    }

    public function getBettingTypeName(): string
    {
        return $this->bettingTypeName;
    }

    public function setBettingTypeName(string $bettingTypeName): void
    {
        $this->bettingTypeName = $bettingTypeName;
    }

    public function getIsOrdered(): bool
    {
        return $this->isOrdered;
    }

    public function setIsOrdered(bool $isOrdered): void
    {
        $this->isOrdered = $isOrdered;
    }
}
