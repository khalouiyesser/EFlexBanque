<?php

namespace App\Entity;

use App\Repository\ReponseCommentaireRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ReponseCommentaireRepository::class)]
class ReponseCommentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message:"Veuillez saisir votre réponse")]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $contenuRepCom = null;
    #[ORM\Column(length: 255)]


    #[Assert\NotBlank(message: 'Veuillez saisir votre nom ')]
    #[Assert\Length(
        min: 3,
        minMessage: 'Votre nom doit contenir au moins {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: '/^[A-Z][a-zA-Z]*$/',
        message: "Votre nom doit commencer par une lettre majuscule."
    )]
    private ?string $nomRepCom = null;

    #[Assert\NotBlank(message:"Veuillez saisir la date")]
   // #[Assert\EqualTo("today", message:"La date de réponse doit être la date d'aujourd'hui.")]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateRepCom = null;

    #[ORM\ManyToOne(inversedBy: 'reponseCommentaires')]
    private ?CommentaireHadhemi $commentaire = null;

    #[ORM\ManyToOne(inversedBy: 'reponseCommentaire')]
    private ?User $user = null;

   

  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getcontenuRepCom (): ?string
    {
        return $this->contenuRepCom ;
    }

    public function setcontenuRepCom (string $contenuRepCom ): static
    {
        $this->contenuRepCom  = $contenuRepCom ;

        return $this;
    }

    public function getCommentaire(): ?CommentaireHadhemi
    {
        return $this->commentaire;
    }

    public function setCommentaire(?CommentaireHadhemi $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }
    public function __toString(){
        return (String)$this->getcontenuRepCom ();
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

   

    public function getNomRepCom(): ?string
    {
        return $this->nomRepCom;
    }

    public function setNomRepCom(string $nomRepCom): static
    {
        $this->nomRepCom = $nomRepCom;

        return $this;
    }

    public function getDateRepCom(): ?\DateTimeInterface
    {
        return $this->dateRepCom;
    }

    public function setDateRepCom(\DateTimeInterface $dateRepCom): static
    {
        $this->dateRepCom = $dateRepCom;

        return $this;
    }
}
