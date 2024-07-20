<?php

namespace App\Entity;

use App\Repository\AgeNameRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AgeNameRepository::class)]
class AgeName
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $ageRange = null;

    #[ORM\Column]
    private ?int $maleCount = null;

    #[ORM\Column]
    private ?int $femaleCount = null;

    #[ORM\Column]
    private ?int $otherCount = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAgeRange(): ?string
    {
        return $this->ageRange;
    }

    public function setAgeRange(string $ageRange): static
    {
        $this->ageRange = $ageRange;

        return $this;
    }

    public function getMaleCount(): ?int
    {
        return $this->maleCount;
    }

    public function setMaleCount(int $maleCount): static
    {
        $this->maleCount = $maleCount;

        return $this;
    }

    public function getFemaleCount(): ?int
    {
        return $this->femaleCount;
    }

    public function setFemaleCount(int $femaleCount): static
    {
        $this->femaleCount = $femaleCount;

        return $this;
    }

    public function getOtherCount(): ?int
    {
        return $this->otherCount;
    }

    public function setOtherCount(int $otherCount): static
    {
        $this->otherCount = $otherCount;

        return $this;
    }
}
