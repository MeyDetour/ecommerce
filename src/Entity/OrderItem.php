<?php

namespace App\Entity;

use App\Repository\OrderItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderItemRepository::class)]
class OrderItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'orderItems')]
    private ?Product $product = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\ManyToOne(inversedBy: 'items')]
    private ?Order $orderCommand = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $number = null;

    /**
     * @var Collection<int, ProductVariant>
     */
    #[ORM\OneToMany(targetEntity: ProductVariant::class, mappedBy: 'orderItem')]
    private Collection $productVariant;

    public function __construct()
    {
        $this->productVariant = new ArrayCollection();
    }





    public function getId(): ?int
    {
        return $this->id;
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

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getOrderCommand(): ?Order
    {
        return $this->orderCommand;
    }

    public function setOrderCommand(?Order $orderCommand): static
    {
        $this->orderCommand = $orderCommand;

        return $this;
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

    /**
     * @return Collection<int, ProductVariant>
     */
    public function getProductVariants(): Collection
    {
        return $this->productVariant;
    }

    public function addProductVariant(ProductVariant $productVariant): static
    {
        if (!$this->productVariant->contains($productVariant)) {
            $this->productVariant->add($productVariant);
            $productVariant->setOrderItem($this);
        }

        return $this;
    }

    public function removeProductVariant(ProductVariant $productVariant): static
    {
        if ($this->productVariant->removeElement($productVariant)) {
            // set the owning side to null (unless already changed)
            if ($productVariant->getOrderItem() === $this) {
                $productVariant->setOrderItem(null);
            }
        }

        return $this;
    }



}
