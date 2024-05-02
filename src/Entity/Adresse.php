<?php

namespace App\Entity;

use App\Repository\AdresseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\TextType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\Extension\Core\Type\TelType;

#[ORM\Entity(repositoryClass: AdresseRepository::class)]
class Adresse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $country = null;

    #[ORM\Column]
    private ?int $codePostal = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $adresse = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $firstName = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $lastName = null;

    #[ORM\Column]
    private ?string $phone = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $city = null;

    #[ORM\ManyToOne(inversedBy: 'adresses')]
    private ?User $owner = null;

    /**
     * @var Collection<int, Order>
     */
    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'adresse')]
    private Collection $orders;

    /**
     * @var Collection<int, Order>
     */
    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'billingAddresse')]
    private Collection $ordersAsBilling;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->ordersAsBilling = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->codePostal;
    }

    public function setCodePostal(int $codePostal): static
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(int $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setAdresse($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getAdresse() === $this) {
                $order->setAdresse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrdersAsBilling(): Collection
    {
        return $this->ordersAsBilling;
    }

    public function addOrdersAsBilling(Order $ordersAsBilling): static
    {
        if (!$this->ordersAsBilling->contains($ordersAsBilling)) {
            $this->ordersAsBilling->add($ordersAsBilling);
            $ordersAsBilling->setBillingAddresse($this);
        }

        return $this;
    }

    public function removeOrdersAsBilling(Order $ordersAsBilling): static
    {
        if ($this->ordersAsBilling->removeElement($ordersAsBilling)) {
            // set the owning side to null (unless already changed)
            if ($ordersAsBilling->getBillingAddresse() === $this) {
                $ordersAsBilling->setBillingAddresse(null);
            }
        }

        return $this;
    }
    public function getEntireName(): string
    {
        return "$this->adresse $this->city $this->codePostal, $this->country";
    }
    public function getEntireIdentity(): string
    {
        return "$this->firstName $this->lastName, $this->phone";
    }
}
