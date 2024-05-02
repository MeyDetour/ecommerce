<?php

namespace App\Entity;

use App\Repository\ProductVariantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductVariantRepository::class)]
class ProductVariant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $number = null;

    #[ORM\ManyToOne(inversedBy: 'productVariants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isSell = null;

    #[ORM\ManyToOne(inversedBy: 'productVariants')]
    private ?Order $command = null;



    #[ORM\ManyToOne(inversedBy: 'productVariant')]
    private ?OrderItem $orderItem = null;

    #[ORM\OneToOne(mappedBy: 'productVariant', cascade: ['persist', 'remove'])]
    private ?Comment $comment = null;



    public function __construct()
    {
     }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function isSell(): ?bool
    {
        return $this->isSell;
    }

    public function setSell(?bool $isSell): static
    {
        $this->isSell = $isSell;

        return $this;
    }

    public function getCommand(): ?Order
    {
        return $this->command;
    }

    public function setCommand(?Order $command): static
    {
        $this->command = $command;

        return $this;
    }


    public function getOrderItem(): ?OrderItem
    {
        return $this->orderItem;
    }

    public function setOrderItem(?OrderItem $orderItem): static
    {
        $this->orderItem = $orderItem;

        return $this;
    }

    public function getComment(): ?Comment
    {
        return $this->comment;
    }

    public function setComment(Comment $comment): static
    {
        // set the owning side of the relation if necessary
        if ($comment->getProductVariant() !== $this) {
            $comment->setProductVariant($this);
        }

        $this->comment = $comment;

        return $this;
    }


}
