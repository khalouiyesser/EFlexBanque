<?php

namespace App\Entity;

use App\Repository\RdvRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: RdvRepository::class)]
class Rdv
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\Length(
        min: 8,
        max: 8,
        exactMessage: "L'ID du client doit être composé de 8 chiffres exactement."
    )]
    private ?int $idclient = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Assert\NotBlank(message: "L'heure est obligatoire.")]
    private ?\DateTimeInterface $heure = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: "La date du rendez-vous est obligatoire.")]
    #[Assert\GreaterThan(
        value: "today",
        message: "La date du rendez-vous doit être supérieure à aujourd'hui."
    )]
    private ?\DateTimeInterface $daterdv = null;

    #[ORM\Column(length: 255)]
    private ?string $methode = null;

    #[ORM\Column(length: 255)]
    private ?string $employename = null;

    #[ORM\ManyToOne(inversedBy: 'rdv')]
    private ?Credit $credit = null;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdClient(): ?int
    {
        return $this->idclient;
    }

    public function setIdClient(int $idclient): static
    {
        $this->idclient = $idclient;

        return $this;
    }

    public function getDateRdv(): ?\DateTimeInterface
    {
        return $this->daterdv;
    }

    public function setDateRdv(\DateTimeInterface $daterdv): static
    {
        $this->daterdv = $daterdv;

        return $this;
    }

    public function getHeure(): ?\DateTimeInterface
    {
        return $this->heure;
    }

    public function setHeure(\DateTimeInterface $heure): static
    {
        $this->heure = $heure;

        return $this;
    }

    public function getMethode(): ?string
    {
        return $this->methode;
    }

    public function setMethode(string $methode): static
    {
        $this->methode = $methode;

        return $this;
    }

    public function getEmployeName(): ?string
    {
        return $this->employename;
    }

    public function setEmployeName(string $employename): static
    {
        $this->employename = $employename;

        return $this;
    }

    public function getCredit(): ?Credit
    {
        return $this->credit;
    }

    public function setCredit(?Credit $credit): static
    {
        $this->credit = $credit;

        return $this;
    }

   // #[Assert\Callback]

   public function getStart(): ?\DateTimeInterface
   {
       return $this->start;
   }

   public function setStart(\DateTimeInterface $start): static
   {
       $this->start = $start;

       return $this;
   }

   public function getEnd(): ?\DateTimeInterface
   {
       return $this->end;
   }

   public function setEnd(\DateTimeInterface $end): static
   {
       $this->end = $end;

       return $this;
   }
  
}

