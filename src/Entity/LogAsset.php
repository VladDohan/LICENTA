<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Asset;
use App\Entity\Traits\Timestampable;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: 'App\Repository\LogAssetRepository')]
#[ORM\Table(name: 'log_asset')]
#[ORM\HasLifecycleCallbacks]
class LogAsset
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: 'App\Entity\Asset', inversedBy: 'log')]
    #[ORM\JoinColumn(nullable: false, name: 'asset_id')]
    private Asset $asset;

    #[ORM\Column(type: 'bigint', precision: 24, scale: 8)]
    #[Assert\Positive(message: 'The buy price must be positive')]
    private string $buyPrice;

    #[ORM\Column(type: 'bigint', precision: 24, scale: 8)]
    #[Assert\Positive(message: 'The buy price must be positive')]
    private string $sellPrice;


    #[ORM\Column(type: 'string', length: 20)]
    private string $quantity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAsset(): Asset
    {
        return $this->asset;
    }

    public function setAsset(Asset $asset): self
    {
        $this->asset = $asset;
        return $this;
    }

    public function getBuyPrice(): string
    {
        return $this->buyPrice;
    }

    public function setBuyPrice(string $buyPrice): self
    {
        $this->buyPrice = $buyPrice;
        return $this;
    }
    public function getSellPrice(): string
    {
        return $this->sellPrice;
    }

    public function setSellPrice(string $sellPrice): self
    {
        $this->sellPrice = $sellPrice;
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

    use Timestampable;
}