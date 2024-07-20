<?php

namespace App\Entity;

use App\Repository\OperativeSystemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OperativeSystemRepository::class)]
class OperativeSystem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $os = null;

    #[ORM\Column]
    private ?int $totalCount = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOs(): ?string
    {
        return $this->os;
    }

    public function setOs(string $os): static
    {
        $this->os = $os;

        return $this;
    }

    public function getTotalCount(): ?int
    {
        return $this->totalCount;
    }

    public function setTotalCount(int $totalCount): static
    {
        $this->totalCount = $totalCount;

        return $this;
    }
}
