<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;



#[ORM\Entity(repositoryClass: ReponseRepository::class)]
class Reponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adrRep = null;
    
    
    
    
    
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateRep = null;
    
    #[Assert\NotBlank(message:"Veuillez saisir le contenu de la réponse")]
    #[ORM\Column(length: 255)]
    private ?string $contenuRep = null;
    
    
    
    #[ORM\Column(length: 255)]
    private ?string $pieceJRep = null;
    
    #[ORM\ManyToOne(inversedBy: 'reponses')]
    private ?Reclamation $reclamation = null;
    
    #[ORM\ManyToOne(inversedBy: 'reponse')]
    private ?User $user = null;
    
    
    
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getAdrRep(): ?string
    {
        return $this->adrRep;
    }
    
    public function setAdrRep(string $adrRep): static
    {
        $this->adrRep = $adrRep;
        
        return $this;
    }
    
    public function getDateRep(): ?\DateTimeInterface
    {
        return $this->dateRep;
    }
    
    public function setDateRep(\DateTimeInterface $dateRep): static
    {
        $this->dateRep = $dateRep;
        
        return $this;
    }
    
    public function getContenuRep(): ?string
    {
        return $this->contenuRep;
    }
    
    public function setContenuRep(string $contenuRep): static
    {
        $this->contenuRep = $contenuRep;
        
        return $this;
    }
    
    
    
    
    
    public function getPieceJRep(): ?string
    {
        return $this->pieceJRep;
    }
    
    public function setPieceJRep(string $pieceJRep): static
    {
        $this->pieceJRep = $pieceJRep;
        
        return $this;
    }
    
    public function getReclamation(): ?Reclamation
    {
        return $this->reclamation;
    }
    
    public function setReclamation(?Reclamation $reclamation): static
    {
        $this->reclamation = $reclamation;
        
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

