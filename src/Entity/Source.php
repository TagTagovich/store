<?php

namespace App\Entity;

use App\Repository\SourceRepository;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SourceRepository::class)
 * @ORM\Table(name="`source`")
 * @Vich\Uploadable
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
     * @Assert\NotBlank(message = "Поле файл не может быть пустым!")
     * 
     */
    private $file;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="sources")
     * 
     */
    private $product;
    
    /**
     * @ORM\ManyToOne(targetEntity=Place::class, inversedBy="sources")
     * @ORM\JoinColumn(name="place_id", referencedColumnName="id")
     */
    private $place;

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



    public function __toString()
    {
        if(is_null($this->getFileName())) {
        
        return 'NULL';
        
        }
        
        return $this->getFileName();
    }

}
