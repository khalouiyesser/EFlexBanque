<?php

namespace App\Entity;

use App\Repository\DemandeStageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Null_;
use PhpParser\Node\Scalar\String_;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: DemandeStageRepository::class)]
class Demandestage
{
    public function __construct()
    {
        // Initialise la date avec la date actuelle
        $this->date = new \DateTime();
    }
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30,nullable: true)]
    #[Assert\NotBlank(message: 'Veuillez saisir votre nom')]
    #[Assert\Length(
        min :3,
        max: 30 ,
        minMessage: "Le nom doit contenir au moins {{ limit }} caractères",
        maxMessage: "Le nom doit contenir au plus {{ limit }} caractères",
    )]
    #[Assert\Regex(pattern: '/[a-zA-Z]/',
        message:' le nom du pack doit contenir que des lettres !!')]
    private ?string $nom = null;
    

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message: 'Veuillez saisir votre prenom')]
    #[Assert\Length(
        min :3,
        max: 30 ,
        minMessage: "Le prénom doit contenir au moins {{ limit }} caractères",
        maxMessage: "Le prénom doit contenir au plus {{ limit }} caractères",
    )]
    #[Assert\Regex(pattern: '/[a-zA-Z]/',
        message:' le prénom du pack doit contenir que des lettres !!')]
    private ?string $prenom = null;

    #[ORM\Column(length: 40)]
    #[Assert\NotBlank(message: 'Email obligatoire')]
    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    private ?string $email = null;
    #[ORM\Column]
    #[Assert\NotBlank(message: 'Veuillez saisir votre numéro de téléphone')]
    #[Assert\Length(min: 8, max: 8, exactMessage: 'Le numéro de téléphone doit contenir 8 chiffres')]
    #[Assert\Regex(pattern: '/^(2|5|9)[0-9]{7}$/', message: 'Le numéro de téléphone doit commencer par 2 ou 5 ou 9 et contenir 8 chiffres')]
    private ?int $numerotelephone = null;
    
    
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\NotBlank(message: 'Veuillez saisir une lettre de motivation')]
    #[Assert\Length(max: 5000,minMessage: 'La lettre de motivation doit etre moins 500 characters ')]
    private ?string $lettremotivation = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\NotBlank(message: 'Veuillez entrer votre CV en pdf ')]
    private ?string $cv = null;
    
    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\NotBlank(message: 'Veuillez choisir un domaine')]
    private ?string $domaine = null;

//    #[ORM\Column( type: Types::DATE_MUTABLE,nullable: true)]
//    #[Assert\GreaterThan(value: "-1 week", message: "Date Invalide !!")]
//    #[Assert\Callback(callback: [self::class, 'validateDate'])]
//   // #[Assert\NotBlank(message: 'Veuillez choisir la date du stage')]
//   // private \DateTimeInterface $date = 2021-02-01;

    #[ORM\Column(length: 10)]
    private ?String $etat = "encours";

    #[ORM\ManyToOne(inversedBy: 'demande')]
    private ?OffreStage $offreStage = null;
    
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\Callback(callback: [self::class, 'validateDate'])]
    private \DateTimeInterface $date;

    #[ORM\Column(nullable: true)]
    private ?float $score = null;
    
//    public function __construct() {
//        $this->date = new \DateTime('2021-02-01');
//    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getNumerotelephone(): ?int
    {
        return $this->numerotelephone;
    }

    public function setNumerotelephone(int $numerotelephone): static
    {
        $this->numerotelephone = $numerotelephone;

        return $this;
    }

    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCv(?string $cv): static
    {
        $this->cv = $cv;

        return $this;
    }

    public function getLettremotivation(): ?string
    {
        return $this->lettremotivation;
    }

    public function setLettremotivation(?string $lettremotivation): static
    {
        $this->lettremotivation = $lettremotivation;

        return $this;
    }

    public function getDomaine(): ?string
    {
        return $this->domaine;
    }

    public function setDomaine(?string $domaine): static
    {
        $this->domaine = $domaine;

        return $this;
    }

//    public function getDate(): ?\DateTimeInterface
//    {
//        return $this->date;
//    }
//
//    public function setDate(\DateTimeInterface $date): static
//    {
//        $this->date = $date;
//
//        return $this;
//    }

    public function getEtat() : ?String
    {
        return $this->etat;
        
    }

    public function setEtat(?String $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getOffreStage(): ?OffreStage
    {
        return $this->offreStage;
    }

    public function setOffreStage(?OffreStage $offreStage): static
    {
        $this->offreStage = $offreStage;

        return $this;
    }


          //Cette fonction donne une condition sur la date !!
    public static function validateDate($value, ExecutionContextInterface $context, $payload): void
    {
        if ($value == Null){
            $context->buildViolation('La date est vide !')
                ->addViolation();
        }
        elseif ($value instanceof \DateTimeInterface ) {
            // La date doit être d'au moins une semaine après la date actuelle
            $today = new \DateTime();
            $oneWeekLater = (clone $today)->modify('+1 week');

            if ($value < $oneWeekLater ) {
                $context->buildViolation('La date doit être au moins une semaine après la date actuelle')
                    ->addViolation();
            }
        }
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getScore(): ?float
    {
        return $this->score;
    }

    public function setScore(float $score): static
    {
        $this->score = $score;

        return $this;
    }
    
}
