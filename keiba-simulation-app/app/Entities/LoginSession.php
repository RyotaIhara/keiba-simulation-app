<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repositories\LoginSessionRepository")]
#[ORM\Table(name: "login_session")]
class LoginSession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\ManyToOne(targetEntity: "App\Entities\User")]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id", nullable: false)]
    private User $user;

    #[ORM\Column(type: "string", name: "token")]
    private ?string $token;

    #[ORM\Column(type: "datetime", name: "expire_time", nullable: true)]
    private ?\DateTime $expireTime;

    #[ORM\Column(type: "datetime", name: "created_at", nullable: true)]
    private ?\DateTime $createdAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function getExpireTime(): ?\DateTime 
    {
        return $this->expireTime;
    }

    public function setExpireTime(?\DateTime  $expireTime): void
    {
        $this->expireTime = $expireTime;
    }

    public function getCreatedAt(): ?\DateTime 
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTime  $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
