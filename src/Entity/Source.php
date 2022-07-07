<?php

namespace App\Entity;

use App\Repository\SourceRepository;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as Valid;
use Imagick;

/**
 * @ORM\Entity(repositoryClass=SourceRepository::class)
 * @ORM\Table(name="`source`")
 * @Vich\Uploadable
 * @Valid\SourceFile
 */
class Source
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     */
    private $fileName;

    /**
     * @Vich\UploadableField(mapping="source_file", fileNameProperty="fileName")
     *
     * @var File
     *
     * 
     */
    public $file;

    /**
     * @ORM\Column(type="integer")
     * 
     */
    public $width;

    /**
     * @ORM\Column(type="integer")
     * 
     * 
     */
    public $height;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * 
     * 
     */
    public $dpi = null;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="sources", cascade={"remove"})
     * 
     */
    private $product;
    
    /**
     * @ORM\ManyToOne(targetEntity=Place::class, inversedBy="sources")
     * @ORM\JoinColumn(name="place_id", referencedColumnName="id")
     */
    public $place;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
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

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file = null): self
    {
        $this->file = $file;
        
        if (null !== $file) {
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(?string $fileName): self
    {
        $this->fileName = $fileName;

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

    public function getDpi(): ?int
    {
        return $this->dpi;
    }

    public function setDpi(int $dpi): self
    {
        $this->dpi = $dpi;

        return $this;
    }

    public function __toString()
    {
        if(is_null($this->getFileName())) {
        
        return 'NULL';
        
        }
        
        return $this->getFileName();
    }

}