<?php

namespace App\Entity;

use App\Repository\SummaryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SummaryRepository::class)]
class Summary
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $totalRecords = null;

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

    public function getTotalRecords(): ?int
    {
        return $this->totalRecords;
    }

    public function setTotalRecords(int $totalRecords): static
    {
        $this->totalRecords = $totalRecords;

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
