<?php

namespace App\Entity;

use App\Repository\StageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: StageRepository::class)]
class Stage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Veuillez entrer le sujet')]
    #[Assert\Length(max: 200,minMessage: 'La lettre de motivation doit etre moins 200 characters ')]
    private ?string $sujet = null;
    
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
//    #[Assert\NotBlank(message: 'Veuillez choisir la date du stage')]
    #[Assert\GreaterThan(value: "today", message: "Date Invalide !!")]
    private ?\DateTimeInterface $date;
    public function __construct()
    {
        $this->date = new \DateTime('2021-02-01');
        $this->users = new ArrayCollection();
    }
//    public function __construct() {
//        $this->date = new \DateTime('2021-02-01');
//    }

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'stage')]
    private Collection $users;

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSujet(): ?string
    {
        return $this->sujet;
    }

    public function setSujet(string $sujet): static
    {
        $this->sujet = $sujet;

        return $this;
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
    public function __toString(): string
    {
       
        return (String)$this->getUsers();
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
            $user->addStage($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeStage($this);
        }

        return $this;
    }
    
}
