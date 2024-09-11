<?php

namespace App\Entity;

use App\Repository\OffreStageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: OffreStageRepository::class)]
class OffreStage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 80)]
    #[Assert\NotBlank(message: 'Veuillez saisir le titre de l\'offre')]
    #[Assert\Length(
        min :20,
        max: 100 ,
        minMessage: "Le titre doit contenir au moins {{ limit }} caractères",
        maxMessage: "Le titre doit contenir au plus {{ limit }} caractères",
    )]
    private ?string $title = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'Veuillez saisir le domaine de l\'offre')]
    private ?string $domaine = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message: 'Veuillez saisir le type de l\'offre')]
    private ?string $typeOffre = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Veuillez saisir le nombre de poste ouvert pour l\'offre')]
    #[Assert\Positive(message:"Veuillez saisir une valeur positive.")]
    private ?int $postePropose = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank(message: 'Veuillez saisir le nombre de poste ouvert pour l\'offre')]
    private ?String $experience = null;

    #[ORM\Column(type: 'json', nullable: true)]
    #[Assert\NotBlank(message: 'Veuillez saisir le niveau demandé pour l\'offre')]
    private ?array $niveau = null;
    
    #[ORM\Column(type: 'json', nullable: true)]
    #[Assert\NotBlank(message: 'Veuillez saisir les languages demandés pour l\'offre')]
    private ?array $language = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Veuillez saisir la description de l\'offre')]
    #[Assert\Length(
        min :200,
        //max: 700 ,
        minMessage: "La description doit contenir au moins {{ limit }} caractères",
        maxMessage: "La description doit contenir au plus {{ limit }} caractères",
    )]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Veuillez saisir les exigences de l\'offre')]
    #[Assert\Length(
        min :100,
        max: 400 ,
        minMessage: "Les exigences doit contenir au moins {{ limit }} caractères",
        maxMessage: "Les exigences doit contenir au plus {{ limit }} caractères",
    )]
    private ?string $exigenceOffre = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\GreaterThan(value: "today", message: "Date Invalide !!")]
    private ?\DateTimeInterface $datePostu = null;

    #[ORM\Column(type: "json", nullable: true)]
    private ?array  $motsCles = null;
    

    #[ORM\OneToMany(mappedBy: 'offreStage', targetEntity: Demandestage::class, orphanRemoval: true)]
    private Collection $demande;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pfeBook = null;

    #[ORM\ManyToOne(inversedBy: 'offreStages')]
    private ?User $user = null;

    public function __construct()
    {
        $this->demande = new ArrayCollection();
        $this->motsCles = [];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDomaine(): ?string
    {
        return $this->domaine;
    }

    public function setDomaine(string $domaine): static
    {
        $this->domaine = $domaine;

        return $this;
    }

    public function getTypeOffre(): ?string
    {
        return $this->typeOffre;
    }

    public function setTypeOffre(string $typeOffre): static
    {
        $this->typeOffre = $typeOffre;

        return $this;
    }

    public function getPostePropose(): ?int
    {
        return $this->postePropose;
    }

    public function setPostePropose(int $postePropose): static
    {
        $this->postePropose = $postePropose;

        return $this;
    }

    public function getExperience(): ?String
    {
        return $this->experience;
    }

    public function setExperience(?String $experience): static
    {
        $this->experience = $experience;

        return $this;
    }

    public function getNiveau(): ?array
    {
        return $this->niveau;
    }

    public function setNiveau(?array $niveau): static
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getLanguage(): ?array
    {
        return $this->language;
    }

    public function setLanguage(?array $language): static
    {
        $this->language = $language;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getExigenceOffre(): ?string
    {
        return $this->exigenceOffre;
    }

    public function setExigenceOffre(string $exigenceOffre): static
    {
        $this->exigenceOffre = $exigenceOffre;

        return $this;
    }

    public function getDatePostu(): ?\DateTimeInterface
    {
        return $this->datePostu;
    }

    public function setDatePostu(?\DateTimeInterface $datePostu): static
    {
        $this->datePostu = $datePostu;

        return $this;
    }

    public function getMotsCles(): array
    {
        return $this->motsCles ?? [];
    }

    public function setMotsCles(?array $motsCles): static
    {
        $this->motsCles = $motsCles;

        return $this;
    }

    /**
     * @return Collection<int, Demandestage>
     */
    public function getDemande(): Collection
    {
        return $this->demande;
    }

    public function addDemande(Demandestage $demande): static
    {
        if (!$this->demande->contains($demande)) {
            $this->demande->add($demande);
            $demande->setOffreStage($this);
        }

        return $this;
    }

    public function removeDemande(Demandestage $demande): static
    {
        if ($this->demande->removeElement($demande)) {
            // set the owning side to null (unless already changed)
            if ($demande->getOffreStage() === $this) {
                $demande->setOffreStage(null);
            }
        }

        return $this;
    }

    public function getPfeBook(): ?string
    {
        return $this->pfeBook;
    }

    public function setPfeBook(?string $pfeBook): static
    {
        $this->pfeBook = $pfeBook;

        return $this;
    }
    public  function  __toString(): string
    {
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
