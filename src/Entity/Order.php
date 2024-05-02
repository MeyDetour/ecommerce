<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    /**
     * @var Collection<int, OrderItem>
     */
    #[ORM\OneToMany(targetEntity: OrderItem::class, mappedBy: 'orderCommand', orphanRemoval: true)]
    private Collection $items;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?float $total = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?int $status = null;
    //0=>WAIT PAYEMENT
    //1=>WAIT DELIVRY
    //2=>DELIVRANCE
    //3=>LIVRED
    //4=>LATE

    #[ORM\Column(type: Types::TEXT)]
    private ?int $payStatus = null;
    //0=>WAIT PAY
    //1=>PAYED
    //2=>
    //3=>

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PayMethode $payment = null;



    #[ORM\ManyToOne(inversedBy: 'ordersAsBilling')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Adresse $adresse = null;

    #[ORM\ManyToOne(inversedBy: 'ordersAsBilling')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Adresse $billingAddresse = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $number = null;

    /**
     * @var Collection<int, ProductVariant>
     */
    #[ORM\OneToMany(targetEntity: ProductVariant::class, mappedBy: 'command')]
    private Collection $productVariants;

    #[ORM\OneToOne(mappedBy: 'command', cascade: ['persist', 'remove'])]
    private ?Action $action = null;



    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->productVariants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection<int, OrderItem>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(OrderItem $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setOrderCommand($this);
        }

        return $this;
    }

    public function removeItem(OrderItem $item): static
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->setOrderCommand() === $this) {
                $item->setOrderCommand(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPayStatus(): ?string
    {
        return $this->payStatus;
    }

    public function setPayStatus(string $payStatus): static
    {
        $this->payStatus = $payStatus;

        return $this;
    }

    public function getPayment(): ?PayMethode
    {
        return $this->payment;
    }

    public function setPayment(?PayMethode $payment): static
    {
        $this->payment = $payment;

        return $this;
    }

    public function getAdresse(): ?Adresse
    {
        return $this->adresse;
    }

    public function setAdresse(?Adresse $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getBillingAddresse(): ?Adresse
    {
        return $this->billingAddresse;
    }

    public function setBillingAddresse(?Adresse $billingAddresse): static
    {
        $this->billingAddresse = $billingAddresse;

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
        return $this->productVariants;
    }

    public function addProductVariant(ProductVariant $productVariant): static
    {
        if (!$this->productVariants->contains($productVariant)) {
            $this->productVariants->add($productVariant);
            $productVariant->setCommand($this);
        }

        return $this;
    }

    public function removeProductVariant(ProductVariant $productVariant): static
    {
        if ($this->productVariants->removeElement($productVariant)) {
            // set the owning side to null (unless already changed)
            if ($productVariant->getCommand() === $this) {
                $productVariant->setCommand(null);
            }
        }

        return $this;
    }

    public function getAction(): ?Action
    {
        return $this->action;
    }

    public function setAction(?Action $action): static
    {
        // unset the owning side of the relation if necessary
        if ($action === null && $this->action !== null) {
            $this->action->setCommand(null);
        }

        // set the owning side of the relation if necessary
        if ($action !== null && $action->getCommand() !== $this) {
            $action->setCommand($this);
        }

        $this->action = $action;

        return $this;
    }




}
