<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: 'integer')]
  private ?int $id = null;
  #[Assert\NotBlank(message: "saisir le contenu ")]
  #[ORM\Column(type: 'text')]
  private ?string $contenu = null;


  #[ORM\ManyToOne(targetEntity: Evenement::class, inversedBy: 'commentaires')]
  private ?Evenement $evenement = null;

  #[ORM\Column(type: 'datetime')]
  private ?\DateTimeInterface $dateCreation = null;


  #[Assert\NotBlank(message: "saisir le contenu ")]
  #[ORM\Column(type: 'string', length: 255, nullable: true)]
  private ?string $nomuser = null;

  #[Assert\NotBlank(message: "saisir le contenu ")]
  #[ORM\Column(type: 'string', length: 255, nullable: true)]
  private ?string $img = null;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getImg(): ?string
  {
    return $this->img;
  }

  public function setImg(string $img): self
  {
    $this->img = $img;

    return $this;
  }

  public function getContenu(): ?string
  {
    return $this->contenu;
  }

  public function setContenu(string $contenu): self
  {
    $this->contenu = $contenu;

    return $this;
  }

  public function getDateCreation(): ?\DateTimeInterface
  {
    return $this->dateCreation;
  }

  public function setDateCreation(\DateTimeInterface $dateCreation): self
  {
    $this->dateCreation = $dateCreation;

    return $this;
  }


  public function getNomuser(): ?string
  {
    return $this->nomuser;
  }

  public function setNomuser(?string $nomuser): self
  {
    $this->nomuser = $nomuser;

    return $this;
  }

  public function getEvenement(): ?Evenement
  {
    return $this->evenement;
  }

  public function setEvenement(?Evenement $evenement): self
  {
    $this->evenement = $evenement;

    return $this;
  }
}
