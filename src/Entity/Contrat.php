<?php

namespace App\Entity;

use App\Repository\ContratRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ContratRepository::class)]
class Contrat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: 'Veuillez saisir la date de début du stage')]
    #[Assert\GreaterThan(value: "today", message: "Date Invalide !!")]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Veuillez saisir la durée du stage')]
    #[Assert\Length(max: 6,minMessage: 'La dure ne contient plus que 6 lettres (comme 2 mois)  ')]
//    #[Assert\Regex(pattern: '/^(1|2|3|4|6|9)\s[a-zA-Z]$/', message: 'La duré doit etre commence par un chiffre (par exemple 1 mois) ')]
    private ?String $dure = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: 'Veuillez saisir la date de fin du stage')]
    #[Assert\GreaterThan(value: "today", message: "Date Invalide !!")]
    private ?\DateTimeInterface $datefin = null;

//    #[ORM\Column(type: Types::TEXT,nullable: true)]
//    #[Assert\NotBlank(message: 'Veuillez saisir le sujet de stage')]
//    #[Assert\Length(max: 200,minMessage: 'Le sujet doit etre moins 200 characters ')]
//    private ?string $sujet = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[Assert\NotBlank(message: 'Veuillez coisir le stagiaire')]
    private ?User $user = null;
    
    
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    
    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDure(): ?String
    {
        return $this->dure;
    }

    public function setDure(String $dure): static
    {
        $this->dure = $dure;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(\DateTimeInterface $datefin): static
    {
        $this->datefin = $datefin;

        return $this;
    }

//    public function getSujet(): ?string
//    {
//        return $this->sujet;
//    }
//
//    public function setSujet(string $sujet): static
//    {
//        $this->sujet = $sujet;
//
//        return $this;
//    }
    public function __toString(){
        return (String)$this->getId();
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
