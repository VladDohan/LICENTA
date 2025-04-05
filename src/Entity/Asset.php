<?php

namespace App\Entity;

use App\Enum\AssetType;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AssetRepository;

#[ORM\Entity(repositoryClass: AssetRepository::class)]
#[ORM\Table(name: 'asset')]
class Asset
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: 'App\Entity\User', inversedBy: 'asset')]
    #[ORM\JoinColumn(nullable: false, name: 'user_id')]
    private User $user;

    #[ORM\Column(type: 'string', enumType: AssetType::class)]
    private AssetType $type;

    #[ORM\Column(type: 'string', length: 20)]
    private string $symbol;

    #[ORM\Column(type: 'decimal', precision: 20, scale: 8)]
    private string $quantity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getType(): AssetType
    {
        return $this->type;
    }

    public function setType(AssetType $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): self
    {
        $this->symbol = $symbol;
        return $this;
    }

    public function getQuantity(): string
    {
        return $this->quantity;
    }

    public function setQuantity(string $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    use \App\Entity\Traits\Timestampable;
}