<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Asset;
use App\Enum\TransactionType;
use App\Entity\Traits\Timestampable;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: 'App\Repository\TransactionRepository')]
#[ORM\Table(name: 'log_asset')]
#[ORM\HasLifecycleCallbacks]
class Transaction
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

    #[ORM\Column(type: 'string', enumType: TransactionType::class)]
    #[Assert\NotBlank]
    private ?TransactionType $transactionType = null;

    #[ORM\Column(type: 'string', length: 20)]
    #[Assert\NotBlank]
    private string $amount;

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

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    use Timestampable;
}