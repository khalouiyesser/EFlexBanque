<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    
    
    #[ORM\Column(length: 255)]
    #[CustomAssert\StartsWithUppercase]
    private ?string $nomAutArt = null;
    
    
    
    #[ORM\Column(length: 255)]
    private ?string $adrAutArt = null;
    
    
    
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datePubArt = null;
    
    
    #[ORM\Column]
    private ?int $dureeArt = null;
    
    
    #[Assert\NotBlank(message:"Veuillez Choisir la catégorie de l'article")]
    #[ORM\Column(length: 255)]
    private ?string $categorieArt = null;
    
    #[Assert\NotBlank(message:"Veuillez saisir le titre de l'article")]
    #[Assert\Regex(
        pattern: '/^[A-Z][a-zA-Z]*$/',
        message: "le titre doit commencer par une lettre majuscule."
    )]
    #[ORM\Column(length: 255)]
    private ?string $titreArt = null;
    
    
    #[Assert\NotBlank(message:"Veuillez saisir le contenu de l'article")]
    #[ORM\Column(length: 255)]
    private ?string $contenuArt = null;
    
    #[Assert\NotBlank(message:"Veuillez entrer une pièce jointe")]
    #[ORM\Column(length: 255)]
    private ?string $piecejointeArt = null;
    
    #[ORM\OneToMany(mappedBy: 'article', targetEntity: CommentaireHadhemi::class)]
    private Collection $commentaire;
    
    
    #[ORM\ManyToOne(inversedBy: 'article')]
    private ?User $user = null;
    
    
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageArt = null;
    
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
    
    
    
    
    
    public function __construct()
    {
        $this->commentaire = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getNomAutArt(): ?string
    {
        return $this->nomAutArt;
    }
    
    public function setNomAutArt(?string $nomAutArt): self
    {
        $this->nomAutArt = $nomAutArt;
        
        return $this;
    }
    
    public function getAdrAutArt(): ?string
    {
        return $this->adrAutArt;
    }
    
    public function setAdrAutArt(?string $adrAutArt): self
    {
        $this->adrAutArt = $adrAutArt;
        
        return $this;
    }
    
    public function getDatePubArt(): ?\DateTimeInterface
    {
        return $this->datePubArt;
    }
    
    public function setDatePubArt(?\DateTimeInterface $datePubArt): self
    {
        $this->datePubArt = $datePubArt;
        
        return $this;
    }
    
    public function getDureeArt(): ?int
    {
        return $this->dureeArt;
    }
    
    public function setDureeArt(?int $dureeArt): self
    {
        $this->dureeArt = $dureeArt;
        
        return $this;
    }
    
    public function getCategorieArt(): ?string
    {
        return $this->categorieArt;
    }
    
    public function setCategorieArt(?string $categorieArt): self
    {
        $this->categorieArt = $categorieArt;
        
        return $this;
    }
    
    public function getTitreArt(): ?string
    {
        return $this->titreArt;
    }
    
    public function setTitreArt(?string $titreArt): self
    {
        $this->titreArt = $titreArt;
        
        return $this;
    }
    
    public function getContenuArt(): ?string
    {
        return $this->contenuArt;
    }
    
    public function setContenuArt(?string $contenuArt): self
    {
        $this->contenuArt = $contenuArt;
        
        return $this;
    }
    
    public function getPiecejointeArt(): ?string
    {
        return $this->piecejointeArt;
    }
    
    public function setPiecejointeArt(?string $piecejointeArt): self
    {
        $this->piecejointeArt = $piecejointeArt;
        
        return $this;
    }
    
    /**
     * @return Collection<int, CommentaireHadhemi>
     */
    public function getCommentaire(): Collection
    {
        return $this->commentaire;
    }
    
    public function addCommentaire(CommentaireHadhemi $commentaire): static
    {
        if (!$this->commentaire->contains($commentaire)) {
            $this->commentaire->add($commentaire);
            $commentaire->setArticle($this);
        }
        
        return $this;
    }
    
    public function removeCommentaire(CommentaireHadhemi $commentaire): static
    {
        if ($this->commentaire->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getArticle() === $this) {
                $commentaire->setArticle(null);
            }
        }
        
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
    
    public function getImageArt(): ?string
    {
        return $this->imageArt;
    }
    
    public function setImageArt(string $imageArt): static
    {
        $this->imageArt = $imageArt;
        
        return $this;
    }
}