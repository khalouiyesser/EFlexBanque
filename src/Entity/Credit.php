<?php

namespace App\Entity;

use App\Repository\CreditRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CreditRepository::class)]
class Credit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le numéro d'inscription est obligatoire.")]
   
    
    private ?int $id_client = null;

    #[ORM\Column]
    #[Assert\GreaterThan(value: 520, message: "Le montant doit être supérieur à 520.")]

    #[Assert\NotBlank(message: "Le montant est obligatoire.")]

    private ?float $montant = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le statut du client est obligatoire.")]
    
    private ?string $statusclient = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "La mensualité est obligatoire.")]
    #[Assert\GreaterThan(value: 840, message: "La mensualité doit être supérieure à 840.")]
    private ?float $mensualite = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: "La date de début est obligatoire.")]
    #[Assert\GreaterThan("today", message: "La date de début doit être ultérieure à aujourd'hui.")]
    private ?\DateTimeInterface $datedebut = null;

    #[ORM\Column]
     #[Assert\NotBlank(message: "La durée est obligatoire.")]
    #[Assert\GreaterThanOrEqual(value: 11, message: "La durée doit être d'au moins 11.")]
    private ?int $duree = null;

    #[ORM\Column]
    private ?float $taux = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column]
    private ?float $fraisretard = null;

    #[ORM\OneToMany(mappedBy: 'credit', targetEntity: Rdv::class,cascade: ['remove'])]
    private Collection $rdv;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fichesalire = null;

    #[ORM\ManyToOne(inversedBy: 'credit')]
    private ?User $user = null;

    public function __construct()
    {
        $this->rdv = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdClient(): ?int
    {
        return $this->id_client;
    }

    public function setIdClient(int $id_client): static
    {
        $this->id_client = $id_client;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getStatusClient(): ?string
    {
        return $this->statusclient;
    }

    public function setStatusClient(string $statusclient): static
    {
        $this->statusclient = $statusclient;

        return $this;
    }

    public function getMensualite(): ?float
    {
        return $this->mensualite;
    }

    public function setMensualite(float $mensualite): static
    {
        $this->mensualite = $mensualite;

        return $this;
    }

    public function getdatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setdatedebut(\DateTimeInterface $datedebut): static
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    public function getTaux(): ?float
    {
        return $this->taux;
    }

    public function setTaux(float $taux): static
    {
        $this->taux = $taux;

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

    public function getfraisretard(): ?float
    {
        return $this->fraisretard;
    }

    public function setfraisretard(float $fraisretard): static
    {
        $this->fraisretard = $fraisretard;

        return $this;
    }

    /**
     * @return Collection<int, rdv>
     */
    public function getRdv(): Collection
    {
        return $this->rdv;
    }

    public function addRdv(rdv $rdv): static
    {
        if (!$this->rdv->contains($rdv)) {
            $this->rdv->add($rdv);
            $rdv->setCredit($this);
        }

        return $this;
    }

    public function removeRdv(rdv $rdv): static
    {
        if ($this->rdv->removeElement($rdv)) {
            // set the owning side to null (unless already changed)
            if ($rdv->getCredit() === $this) {
                $rdv->setCredit(null);
            }
        }

        return $this;
    }

    public function getFichesalire(): ?string
    {
        return $this->fichesalire;
    }

    public function setFichesalire(?string $fichesalire): static
    {
        $this->fichesalire = $fichesalire;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
