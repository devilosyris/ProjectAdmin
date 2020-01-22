<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InvoiceRepository")
 * @ApiResource(
 * normalizationContext={"groups"={"invoices_read"}}
 * )
 */
class Invoice
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"invoices_read", "user_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     * @Groups({"invoices_read", "user_read"})
     */
    private $amount;

    /**
     * @ORM\Column(type="boolean")
     */
    private $paid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pdf;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $expiry;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="invoices")
     * @Groups({"invoices_read"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Invoice", inversedBy="monthly")
     */
    private $invoice;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Invoice", mappedBy="invoice")
     */
    private $monthly;

    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
        $this->invoices = new ArrayCollection();
        $this->monthly = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getPaid(): ?bool
    {
        return $this->paid;
    }

    public function setPaid(bool $paid): self
    {
        $this->paid = $paid;

        return $this;
    }

    public function getPdf(): ?string
    {
        return $this->pdf;
    }

    public function setPdf(string $pdf): self
    {
        $this->pdf = $pdf;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getExpiry(): ?\DateTimeInterface
    {
        return $this->expiry;
    }

    public function setExpiry(\DateTimeInterface $expiry): self
    {
        $this->expiry = $expiry;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getInvoice(): ?self
    {
        return $this->invoice;
    }

    public function setInvoice(?self $invoice): self
    {
        $this->invoice = $invoice;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getMonthly(): Collection
    {
        return $this->monthly;
    }

    public function addMonthly(self $monthly): self
    {
        if (!$this->monthly->contains($monthly)) {
            $this->monthly[] = $monthly;
            $monthly->setInvoice($this);
        }

        return $this;
    }

    public function removeMonthly(self $monthly): self
    {
        if ($this->monthly->contains($monthly)) {
            $this->monthly->removeElement($monthly);
            // set the owning side to null (unless already changed)
            if ($monthly->getInvoice() === $this) {
                $monthly->setInvoice(null);
            }
        }

        return $this;
    }


}
