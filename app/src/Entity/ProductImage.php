<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductImageRepository;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ProductImageRepository::class)]
#[Vich\Uploadable]
class ProductImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Vich\UploadableField(
        mapping: 'product', 
        fileNameProperty: 'name', 
        size: 'size', 
        mimeType: 'mimeType', 
        dimensions: 'dimensions'
    )]
    private ?File $file = null;

    #[ORM\Column(nullable: true)]
    private ?int $size = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mimeType = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $dimensions = null;

    #[ORM\ManyToOne(inversedBy: 'productImages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $productId = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $addDate = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $lastUpdate = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(?File $file = null): static
    {
        $this->file = $file;

        if (null !== $file) {
            $this->lastUpdate = new \DateTimeImmutable();
        }

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(int $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(string $mimeType): static
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getProductId(): ?Product
    {
        return $this->productId;
    }

    public function setProductId(?Product $productId): static
    {
        $this->productId = $productId;

        return $this;
    }

    public function getAddDate(): ?\DateTimeImmutable
    {
        return $this->addDate;
    }

    public function setAddDate(\DateTimeImmutable $addDate): static
    {
        $this->addDate = $addDate;

        return $this;
    }

    public function getLastUpdate(): ?\DateTimeImmutable
    {
        return $this->lastUpdate;
    }

    public function setLastUpdate(\DateTimeImmutable $lastUpdate): static
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    public function getDimensions(): ?array
    {
        return $this->dimensions;
    }

    public function setDimensions(?array $dimensions): static
    {
        $this->dimensions = $dimensions;

        return $this;
    }
}
