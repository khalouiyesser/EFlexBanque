<?php

namespace App\Entity;
use App\Repository\EvenementRepository;
use Doctrine\ORM\Mapping as ORM;
#[ORM\Entity(repositoryClass: EvenementRepository::class)]
class Evenement
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: 'integer')]
  private $id;

  #[ORM\Column(type: 'string', length: 255)]
  private $nom;

  #[ORM\OneToMany(targetEntity: Commentaire::class, mappedBy: 'evenement', cascade: ['remove'])]
  private $commentaires;

  #[ORM\Column(type: 'text')]
  private $description;

  #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: 'projects')]
  private ?Project $project = null;

  #[ORM\Column(type: 'datetime')]
  private $dateDebut;

  #[ORM\Column(type: 'datetime')]
  private $dateFin;

  #[ORM\Column(type: 'string', length: 255)]
  private $lieu;

  #[ORM\Column(type: 'string', length: 255)]
  private $organisateur;

  #[ORM\Column(type: 'string', length: 255)]
  private $img;

  #[ORM\Column(type: 'float')]
  private $prix;


  #[ORM\Column(type: 'integer')]
  private int $likes = 0;

  #[ORM\Column(type: 'integer')]
  private int $dislikes = 0;



  public function getLikes(): int
  {
    return $this->likes;
  }

  public function setLikes(int $likes): self
  {
    $this->likes = $likes;

    return $this;
  }

  public function getDislikes(): int
  {
    return $this->dislikes;
  }

  public function setDislikes(int $dislikes): self
  {
    $this->dislikes = $dislikes;

    return $this;
  }


  public function getId(): ?int
  {
    return $this->id;
  }

  public function getNom(): ?string
  {
    return $this->nom;
  }

  public function setNom(string $nom): self
  {
    $this->nom = $nom;

    return $this;
  }

  public function getDescription(): ?string
  {
    return $this->description;
  }

  public function setDescription(string $description): self
  {
    $this->description = $description;

    return $this;
  }

  public function getDateDebut(): ?\DateTimeInterface
  {
    return $this->dateDebut;
  }

  public function setDateDebut(\DateTimeInterface $dateDebut): self
  {
    $this->dateDebut = $dateDebut;

    return $this;
  }

  public function getDateFin(): ?\DateTimeInterface
  {
    return $this->dateFin;
  }

  public function setDateFin(\DateTimeInterface $dateFin): self
  {
    $this->dateFin = $dateFin;

    return $this;
  }

  public function getLieu(): ?string
  {
    return $this->lieu;
  }

  public function setLieu(string $lieu): self
  {
    $this->lieu = $lieu;

    return $this;
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

  public function getOrganisateur(): ?string
  {
    return $this->organisateur;
  }

  public function setOrganisateur(string $organisateur): self
  {
    $this->organisateur = $organisateur;

    return $this;
  }

  public function getPrix(): ?float
  {
    return $this->prix;
  }

  public function setPrix(float $prix): self
  {
    $this->prix = $prix;

    return $this;
  }
  public function getProject(): ?Project
  {
    return $this->project;
  }

  public function setProject(?Project $project): self
  {
    $this->project = $project;

    return $this;
  }
  public function getCommentaires()
  {
    return $this->commentaires;
  }

  public function addCommentaire(Commentaire $commentaire): self
  {
    if (!$this->commentaires->contains($commentaire)) {
      $this->commentaires[] = $commentaire;
      $commentaire->setEvenement($this);
    }

    return $this;
  }

  public function removeCommentaire(Commentaire $commentaire): self
  {
    if ($this->commentaires->removeElement($commentaire)) {
      // set the owning side to null (unless already changed)
      if ($commentaire->getEvenement() === $this) {
        $commentaire->setEvenement(null);
      }
    }

    return $this;
  }



}
