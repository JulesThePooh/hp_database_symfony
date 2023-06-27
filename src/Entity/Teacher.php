<?php

namespace App\Entity;

use App\Repository\TeacherRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeacherRepository::class)]
class Teacher
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\OneToMany(mappedBy: 'teacher', targetEntity: TypeOfClass::class)]
    private Collection $typeOfClasses;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    public function __construct()
    {
        $this->typeOfClasses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, TypeOfClass>
     */
    public function getGrades(): Collection
    {
        return $this->typeOfClasses;
    }

    public function addGrade(TypeOfClass $typeOfClass): static
    {
        if (!$this->typeOfClasses->contains($typeOfClass)) {
            $this->typeOfClasses->add($typeOfClass);
            $typeOfClass->setTeacher($this);
        }

        return $this;
    }

    public function removeGrade(TypeOfClass $typeOfClass): static
    {
        if ($this->typeOfClasses->removeElement($typeOfClass)) {
            // set the owning side to null (unless already changed)
            if ($typeOfClass->getTeacher() === $this) {
                $typeOfClass->setTeacher(null);
            }
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
