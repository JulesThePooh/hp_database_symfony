<?php

namespace App\Entity;

use App\Repository\HouseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HouseRepository::class)]
class House
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $houseName = null;

    #[ORM\Column(length: 255)]
    private ?string $founderFirstName = null;

    #[ORM\Column(length: 255)]
    private ?string $founderLastName = null;

    #[ORM\OneToMany(mappedBy: 'house', targetEntity: Student::class)]
    private Collection $students;

    #[ORM\OneToMany(mappedBy: 'house', targetEntity: HousePoint::class)]
    private Collection $housePoints;

    public function __construct()
    {
        $this->students = new ArrayCollection();
        $this->housePoints = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHouseName(): ?string
    {
        return $this->houseName;
    }

    public function setHouseName(string $houseName): static
    {
        $this->houseName = $houseName;

        return $this;
    }

    public function getFounderFirstName(): ?string
    {
        return $this->founderFirstName;
    }

    public function setFounderFirstName(string $founderFirstName): static
    {
        $this->founderFirstName = $founderFirstName;

        return $this;
    }

    public function getFounderLastName(): ?string
    {
        return $this->founderLastName;
    }

    public function setFounderLastName(string $founderLastName): static
    {
        $this->founderLastName = $founderLastName;

        return $this;
    }

    /**
     * @return Collection<int, Student>
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(Student $student): static
    {
        if (!$this->students->contains($student)) {
            $this->students->add($student);
            $student->setHouse($this);
        }

        return $this;
    }

    public function removeStudent(Student $student): static
    {
        if ($this->students->removeElement($student)) {
            // set the owning side to null (unless already changed)
            if ($student->getHouse() === $this) {
                $student->setHouse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, HousePoint>
     */
    public function getHousePoints(): Collection
    {
        return $this->housePoints;
    }

    public function addHousePoint(HousePoint $housePoint): static
    {
        if (!$this->housePoints->contains($housePoint)) {
            $this->housePoints->add($housePoint);
            $housePoint->setHouse($this);
        }

        return $this;
    }

    public function removeHousePoint(HousePoint $housePoint): static
    {
        if ($this->housePoints->removeElement($housePoint)) {
            // set the owning side to null (unless already changed)
            if ($housePoint->getHouse() === $this) {
                $housePoint->setHouse(null);
            }
        }

        return $this;
    }
}
