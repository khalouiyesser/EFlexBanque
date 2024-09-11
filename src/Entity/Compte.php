<?php

namespace App\Entity;

use App\Repository\CompteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CompteRepository::class)]
class Compte
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Email obligatoire')]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/",
        message: "L'adresse email '{{ value }}' n'est pas valide."
    )]
    private ?string $Email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Email obligatoire')]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/",
        message: "L'adresse email '{{ value }}' n'est pas valide."
    )]
    private ?string $confirmationEmail = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Veuillez saisir numéro de votre cin')]
    #[Assert\Length(min: 8, max: 8, exactMessage: 'Le numéro de cin doit contenir 8 chiffres')]
    #[Assert\Regex(pattern: '/^(1|0)[0-9]{7}$/', message: 'Le numéro de cin doit commencer par 1 ou 0 ')]
    private ?int $cin = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\LessThan(value: "today", message: "La date de délivrance du CIN ne peut pas être dans le futur")]
    #[Assert\LessThanOrEqual(value: "now", message: "L'année de délivrance du CIN ne peut pas être supérieure à 2024")]
    private ?\DateTimeInterface $DateDelivranceCin = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Votre nom et prénom sont importants pour mieux collaborer avec vous  ')]
    #[Assert\Length(
        min :3,
        max: 30 ,
        minMessage: "Le nom doit contenir au moins {{ limit }} caractères",
        maxMessage: "Le nom doit contenir au plus {{ limit }} caractères",
    )]
    #[Assert\Regex(pattern: '/[a-zA-Z]/',
        message:' le nom du pack doit contenir que des lettres !!')]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Votre nom et prénom sont importants pour mieux collaborer avec vous')]
    #[Assert\Length(
        min :3,
        max: 30 ,
        minMessage: "Le prénom doit contenir au moins {{ limit }} caractères",
        maxMessage: "Le prénom doit contenir au plus {{ limit }} caractères",
    )]
    #[Assert\Regex(pattern: '/[a-zA-Z]/',
        message:' le prénom du pack doit contenir que des lettres !!')]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez choisir votre genre')]
    private ?string $sexe = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\LessThanOrEqual("today", message: "La date de naissance ne peut pas être postérieure à aujourd'hui.")]
    private ?\DateTimeInterface $DateNaissance = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez saisir votre proffesion  ')]
    private ?string $proffesion = null;

    #[ORM\Column(length: 255)]
    private ?string $typeCompte = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Veuillez saisir votre montant de depot  ')]
    private ?float $Montant = null;

    #[ORM\OneToMany(mappedBy: 'compte', targetEntity: Cheque::class, orphanRemoval: true)]
    private Collection $idCheque;

    #[ORM\OneToMany(mappedBy: 'compte', targetEntity: Virement::class, orphanRemoval: true)]
    private Collection $idVirement;

    #[ORM\Column(length: 255)]
    private ?string $StatutMarital = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez saisir votre Nationalité  ')]
    private ?string $nationalite = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Veuillez saisir votre numéro de téléphone')]
    #[Assert\Length(min: 8, max: 8, exactMessage: 'Le numéro de téléphone doit contenir 8 chiffres')]
    #[Assert\Regex(pattern: '/^(2|5|9)[0-9]{7}$/', message: 'Le numéro de téléphone doit commencer par 2 ou 5 ou 9 et contenir 8 chiffres')]
    private ?int $NumeroTelephone = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez choisir votre pref de communication ')]
    private ?string $PreferenceCommunic = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez saisir le type de votre cin')]
    private ?string $TypeCin = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?string $RIB = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'compte')]
    private Collection $users;

    #[ORM\Column]
    private ?int $statut = 0;



    public function __construct()
    {
        $this->idCheque = new ArrayCollection();
        $this->idVirement = new ArrayCollection();
        $this->users = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): static
    {
        $this->Email = $Email;

        return $this;
    }

    public function getConfirmationEmail(): ?string
    {
        return $this->confirmationEmail;
    }

    public function setConfirmationEmail(string $confirmationEmail): static
    {
        $this->confirmationEmail = $confirmationEmail;

        return $this;
    }

    public function getCin(): ?int
    {
        return $this->cin;
    }

    public function setCin(int $cin): static
    {
        $this->cin = $cin;

        return $this;
    }

    public function getDateDelivranceCin(): ?\DateTimeInterface
    {
        return $this->DateDelivranceCin;
    }

    public function setDateDelivranceCin(\DateTimeInterface $DateDelivranceCin): static
    {
        $this->DateDelivranceCin = $DateDelivranceCin;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): static
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->DateNaissance;
    }

    public function setDateNaissance(\DateTimeInterface $DateNaissance): static
    {
        $this->DateNaissance = $DateNaissance;

        return $this;
    }

    public function getProffesion(): ?string
    {
        return $this->proffesion;
    }

    public function setProffesion(string $proffesion): static
    {
        $this->proffesion = $proffesion;

        return $this;
    }

    public function getTypeCompte(): ?string
    {
        return $this->typeCompte;
    }

    public function setTypeCompte(string $typeCompte): static
    {
        $this->typeCompte = $typeCompte;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->Montant;
    }

    public function setMontant(float $Montant): static
    {
        $this->Montant = $Montant;

        return $this;
    }

    /**
     * @return Collection<int, Cheque>
     */
    public function getIdCheque(): Collection
    {
        return $this->idCheque;
    }

    public function addIdCheque(Cheque $idCheque): static
    {
        if (!$this->idCheque->contains($idCheque)) {
            $this->idCheque->add($idCheque);
            $idCheque->setCompte($this);
        }

        return $this;
    }

    public function removeIdCheque(Cheque $idCheque): static
    {
        if ($this->idCheque->removeElement($idCheque)) {
            // set the owning side to null (unless already changed)
            if ($idCheque->getCompte() === $this) {
                $idCheque->setCompte(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Virement>
     */
    public function getIdVirement(): Collection
    {
        return $this->idVirement;
    }

    public function addIdVirement(Virement $idVirement): static
    {
        if (!$this->idVirement->contains($idVirement)) {
            $this->idVirement->add($idVirement);
            $idVirement->setCompte($this);
        }

        return $this;
    }

    public function removeIdVirement(Virement $idVirement): static
    {
        if ($this->idVirement->removeElement($idVirement)) {
            // set the owning side to null (unless already changed)
            if ($idVirement->getCompte() === $this) {
                $idVirement->setCompte(null);
            }
        }

        return $this;
    }
    public function __toString(){
        return (String)$this->getRIB();
    }

    public function  toString(){
        return (String)$this->getTypeCompte();
    }

    public function getStatutMarital(): ?string
    {
        return $this->StatutMarital;
    }

    public function setStatutMarital(string $StatutMarital): static
    {
        $this->StatutMarital = $StatutMarital;

        return $this;
    }

    public function getNationalite(): ?string
    {
        return $this->nationalite;
    }

    public function setNationalite(string $nationalite): static
    {
        $this->nationalite = $nationalite;

        return $this;
    }

    public function getNumeroTelephone(): ?int
    {
        return $this->NumeroTelephone;
    }

    public function setNumeroTelephone(int $NumeroTelephone): static
    {
        $this->NumeroTelephone = $NumeroTelephone;

        return $this;
    }

    public function getPreferenceCommunic(): ?string
    {
        return $this->PreferenceCommunic;
    }

    public function setPreferenceCommunic(string $PreferenceCommunic): static
    {
        $this->PreferenceCommunic = $PreferenceCommunic;

        return $this;
    }

    public function getTypeCin(): ?string
    {
        return $this->TypeCin;
    }

    public function setTypeCin(string $TypeCin): static
    {
        $this->TypeCin = $TypeCin;

        return $this;
    }

    public function getRIB(): ?string
    {
        return $this->RIB;
    }

    public function setRIB(?string $RIB): static
    {
        $this->RIB = $RIB;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addCompte($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeCompte($this);
        }

        return $this;
    }

    public function getStatut(): ?int
    {
        return $this->statut;
    }

    public function setStatut(?int $statut): static
    {
        $this->statut = $statut;

        return $this;
    }




}
