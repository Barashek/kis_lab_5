<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VacancyRepository")
 */
class Vacancy
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $description;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $minSalary;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $maxSalary;

    /**
     * @ORM\Column(type="integer")
     */
    private $minExperience;

    /**
     * @ORM\Column(type="integer")
     */
    private $maxExperience;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="vacancies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $company;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getMinSalary(): ?float
    {
        return $this->minSalary;
    }

    public function setMinSalary(?float $minSalary): self
    {
        $this->minSalary = $minSalary;

        return $this;
    }

    public function getMaxSalary(): ?float
    {
        return $this->maxSalary;
    }

    public function setMaxSalary(?float $maxSalary): self
    {
        $this->maxSalary = $maxSalary;

        return $this;
    }

    public function getMinExperience(): ?int
    {
        return $this->minExperience;
    }

    public function setMinExperience(int $minExperience): self
    {
        $this->minExperience = $minExperience;

        return $this;
    }

    public function getMaxExperience(): ?int
    {
        return $this->maxExperience;
    }

    public function setMaxExperience(int $maxExperience): self
    {
        $this->maxExperience = $maxExperience;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }
}
