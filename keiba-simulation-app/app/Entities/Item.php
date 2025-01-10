<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

#[ORM\Entity(repositoryClass: "App\Repositories\ItemRepository")]
#[ORM\Table(name: "item")]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", name: "item_name")]
    private string $itemName;

    #[ORM\Column(type: "integer")]
    private int $amount;

    #[ORM\Column(type: "integer")]
    private int $count;

    #[ORM\Column(type: "datetime", name: "created_at")]
    private DateTime $createdAt;

    #[ORM\Column(type: "datetime", name: "updated_at")]
    private DateTime $updatedAt;

    // Getter and Setter for ID
    public function getId(): int
    {
        return $this->id;
    }

    // Getter and Setter for Item Name
    public function getItemName(): string
    {
        return $this->itemName;
    }

    public function setItemName(string $itemName): void
    {
        $this->itemName = $itemName;
    }

    // Getter and Setter for Amount
    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    // Getter and Setter for Count
    public function getCount(): int
    {
        return $this->count;
    }

    public function setCount(int $count): void
    {
        $this->count = $count;
    }

    // Getter and Setter for CreatedAt
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    // Getter and Setter for UpdatedAt
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
