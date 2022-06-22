<?php

namespace App\Entity;

use App\Repository\BaseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BaseRepository::class)
 * @ORM\Table(name="`base`")
 */
class Base
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank(message = "Поле не может быть пустым!")
     */
    private $name;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * 
     * @Assert\NotBlank(message = "Поле не может быть пустым!")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Place", mappedBy="base", orphanRemoval=true, cascade={"persist"})
     * @Assert\Valid() 
     */
    private $places;

    public function __construct()
    {
        $this->places = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|Place[]
     */
    public function getPlaces(): Collection
    {
        return $this->places;
    }

    public function addPlace(Place $place): self
    {
        if (!$this->places->contains($place)) {
            $this->places[] = $place;
            $place->setBase($this);
        }

        return $this;
    }

    public function removePlace(Place $place): self
    {
        if ($this->places->removeElement($place)) {
            // set the owning side to null (unless already changed)
            if ($place->getBase() === $this) {
                $place->setBase(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
