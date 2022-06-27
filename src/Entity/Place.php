<?php

namespace App\Entity;

use App\Repository\PlaceRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @Vich\Uploadable
 * @ORM\Entity(repositoryClass=PlaceRepository::class)
 * @ORM\Table(name="`place`")
 */
class Place
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Поле не может быть пустым!")
     * 
     * 
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     *
     */ 
    private $startX = 0;

    /**
     * @ORM\Column(type="integer")
     *
     */ 
    private $startY = 0;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message = "Поле не может быть пустым!")
     * 
     */
    private $width;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message = "Поле не может быть пустым!")
     * 
     */
    private $height;
    
    //ORM\JoinColumn(name="base_id", referencedColumnName="id", onDelete="SET NULL")
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Base", inversedBy="places")
     * 
     */
    private $base;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * 
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     */
    private $imageFilename;
    
    /**
     * @Vich\UploadableField(mapping="place_image", fileNameProperty="imageFilename")
     *
     * @var File
     * 
     * 
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity=Source::class, mappedBy="place") 
     *  
     */
    private $sources;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Photo", inversedBy="place")
     * @ORM\JoinColumn(name="photo_id", referencedColumnName="id")
     */
    private $photo;

    public function __construct()
    {
        $this->sources = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getImage(): ?File
    {
        return $this->image;
    }

    public function setImage(?File $image = null): self
    {
        $this->image = $image;
        
        if (null !== $image) {
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    public function getImageFilename(): ?string
    {
        return $this->imageFilename;
    }

    public function setImageFilename(?string $imageFilename): self
    {
        $this->imageFilename = $imageFilename;

        return $this;
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

    public function getStartX(): ?int
    {
        return $this->startX;
    }

    public function setStartX(int $startX): self
    {
        $this->startX = $startX;

        return $this;
    }

    public function getStartY(): ?int
    {
        return $this->startY;
    }

    public function setStartY(int $startY): self
    {
        $this->startY = $startY;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getBase(): ?Base
    {
        return $this->base;
    }

    public function setBase(?Base $base): self
    {
        $this->base = $base;

        return $this;
    }

    public function getPhoto(): ?Photo
    {
        return $this->photo;
    }

    public function setPhoto(?Photo $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return Collection<int, Source>
     */
    public function getSources(): Collection
    {
        return $this->sources;
    }

    public function addSource(Source $source): self
    {
        if (!$this->sources->contains($source)) {
            $this->sources[] = $source;
            $source->setPlace($this);
        }

        return $this;
    }

    public function removeSource(Source $source): self
    {
        if ($this->sources->removeElement($source)) {
            // set the owning side to null (unless already changed)
            if ($source->getPlace() === $this) {
                $source->setPlace(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
    
        return $this->getName();
    }
}
