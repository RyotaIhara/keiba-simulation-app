<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repositories\SettingServiceRepository")]
#[ORM\Table(name: "setting_service")]
class SettingService
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", name: "setting_key ")]
    private ?string $settingKey  = '';

    #[ORM\Column(type: "string", name: "setting_value")]
    private ?string $settingValue = '';

    public function getId(): int
    {
        return $this->id;
    }

    public function getSettingKey(): string
    {
        return $this->settingKey;
    }

    public function setSettingKey(string $settingKey): void
    {
        $this->settingKey = $settingKey;
    }

    public function getSettingValue(): string
    {
        return $this->settingValue;
    }

    public function setSettingValue(string $settingValue): void
    {
        $this->settingValue = $settingValue;
    }
}
