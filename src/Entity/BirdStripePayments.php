<?php

namespace App\Entity;

use App\Repository\BirdStripePaymentsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BirdStripePaymentsRepository::class)]
class BirdStripePayments
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: 'integer')]
  private $id;

  #[ORM\Column(type: 'string', length: 255)]
  private $name;

  #[ORM\Column(type: 'string', length: 255)]
  private $email;

  #[ORM\Column(type: 'string', length: 12)]
  private $contact;

  #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
  private $amount;

  #[ORM\Column(type: 'string', length: 12)]
  private $paymentStatus;

  #[ORM\Column(type: 'text', nullable: true)]
  private $paymentIntent;

  #[ORM\Column(type: 'datetime')]
  private $createdAt;

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

  public function getEmail(): ?string
  {
    return $this->email;
  }

  public function setEmail(string $email): self
  {
    $this->email = $email;

    return $this;
  }

  public function getContact(): ?string
  {
    return $this->contact;
  }

  public function setContact(string $contact): self
  {
    $this->contact = $contact;

    return $this;
  }

  public function getAmount(): ?string
  {
    return $this->amount;
  }

  public function setAmount(string $amount): self
  {
    $this->amount = $amount;

    return $this;
  }

  public function getPaymentStatus(): ?string
  {
    return $this->paymentStatus;
  }

  public function setPaymentStatus(string $paymentStatus): self
  {
    $this->paymentStatus = $paymentStatus;

    return $this;
  }

  public function getPaymentIntent(): ?string
  {
    return $this->paymentIntent;
  }

  public function setPaymentIntent(?string $paymentIntent): self
  {
    $this->paymentIntent = $paymentIntent;

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
}
