<?php

namespace App\Entity;

use App\Repository\PropretyseachRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PropretyseachRepository::class)]
class Propretyseach
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    private $id_client;
    public function getIdClient(): ?int
    {
        return $this->id_client;
    }

    public function setIdClient(int $id_client): static
    {
        $this->id_client = $id_client;

        return $this;
    }
}
