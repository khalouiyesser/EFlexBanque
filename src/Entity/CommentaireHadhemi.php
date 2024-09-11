<?php

namespace App\Entity;

use App\Repository\CommentaireHadhemiRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentaireHadhemiRepository::class)]
class CommentaireHadhemi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;
    #[Assert\NotBlank(message:"Veuillez saisir votre commentaireHadhemi")]
    #[ORM\Column(type: 'text')]
    private ?string $contenu = null;


    #[Assert\NotBlank(message:"Veuillez saisir la date")]
    #[ORM\Column(type: 'datetime')]
   // #[Assert\EqualTo("today", message:"La date de commentaireHadhemi doit être la date d'aujourd'hui.")]

    private ?\DateTimeInterface $dateCreation = null;

    #[Assert\NotBlank(message: 'Veuillez saisir votre nom ')]
    #[Assert\Length(
        min: 3,
        minMessage: 'Votre nom doit contenir au moins {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: '/^[A-Z][a-zA-Z]*$/',
        message: "Votre nom doit commencer par une lettre majuscule."
    )]
    #[ORM\Column(length: 255)]
    private ?string $nomAutCom = null;

    #[ORM\ManyToOne(inversedBy: 'commentaireHadhemi')]
    private ?Article $article = null;

    #[ORM\OneToMany(mappedBy: 'commentaireHadhemi', targetEntity: ReponseCommentaire::class)]
    private Collection $reponseCommentaires;

    #[ORM\ManyToOne(inversedBy: 'commentaireHadhemi')]
    private ?User $user = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageU = null;

    public function __construct()
    {
        $this->reponseCommentaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNomAutCom(): ?string
    {
        return $this->nomAutCom;
    }

    public function setNomAutCom(string $nomAutCom): static
    {
        $this->nomAutCom = $nomAutCom;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): static
    {
        $this->article = $article;

        return $this;
    }

    /**
     * @return Collection<int, ReponseCommentaire>
     */
    public function getReponseCommentaires(): Collection
    {
        return $this->reponseCommentaires;
    }

    public function addReponseCommentaire(ReponseCommentaire $reponseCommentaire): static
    {
        if (!$this->reponseCommentaires->contains($reponseCommentaire)) {
            $this->reponseCommentaires->add($reponseCommentaire);
            $reponseCommentaire->setCommentaire($this);
        }

        return $this;
    }

    public function removeReponseCommentaire(ReponseCommentaire $reponseCommentaire): static
    {
        if ($this->reponseCommentaires->removeElement($reponseCommentaire)) {
            // set the owning side to null (unless already changed)
            if ($reponseCommentaire->getCommentaire() === $this) {
                $reponseCommentaire->setCommentaire(null);
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

    public function getImageU(): ?string
    {
        return $this->imageU;
    }

    public function setImageU(?string $imageU): static
    {
        $this->imageU = $imageU;

        return $this;
    }
}