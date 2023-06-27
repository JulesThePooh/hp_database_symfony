<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
class Student
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;


    #[ORM\ManyToMany(targetEntity: TypeOfClass::class, inversedBy: 'students')]
    private Collection $typeOfClasses;

    #[ORM\ManyToOne(inversedBy: 'students')]
    #[ORM\JoinColumn(nullable: false)]
    private ?House $house = null;

    #[ORM\Column]
    private ?int $yearOfBirth = null;

    #[ORM\Column]
    private ?bool $isAlive = null;

    public function __construct()
    {
        $this->typeOfClasses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Collection<int, TypeOfClass>
     */
    public function getClasses(): Collection
    {
        return $this->typeOfClasses;
    }

    public function addClass(TypeOfClass $typeOfClass): static
    {
        if (!$this->typeOfClasses->contains($typeOfClass)) {
            $this->typeOfClasses->add($typeOfClass);
        }

        return $this;
    }

    public function removeClass(TypeOfClass $typeOfClass): static
    {
        $this->typeOfClasses->removeElement($typeOfClass);

        return $this;
    }

    public function getHouse(): ?House
    {
        return $this->house;
    }

    public function setHouse(?House $house): static
    {
        $this->house = $house;

        return $this;
    }

    public function getYearOfBirth(): ?int
    {
        return $this->yearOfBirth;
    }

    public function setYearOfBirth(int $yearOfBirth): static
    {
        $this->yearOfBirth = $yearOfBirth;

        return $this;
    }

    public function isIsAlive(): ?bool
    {
        return $this->isAlive;
    }

    public function setIsAlive(bool $isAlive): static
    {
        $this->isAlive = $isAlive;

        return $this;
    }
}
