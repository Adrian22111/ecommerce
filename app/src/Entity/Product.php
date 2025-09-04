<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[UniqueEntity(fields: 'symbol', message: 'symbol_used')]
#[Vich\Uploadable]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "value_not_blank")]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: "min_length",
        maxMessage: 'max_length',
    )]
    private ?string $name = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "value_not_blank")]
    #[Assert\Range(
        min: 1,
        max: 1000000,
        notInRangeMessage: 'price_not_in_range',
    )]
    private ?int $price = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $add_date = null;

    #[ORM\Column]
    private ?\DateTime $last_update = null;

    #[ORM\Column(length: 2000, nullable: true)]
    #[Assert\NotBlank(message: "value_not_blank")]
    #[Assert\Length(
        min: 1,
        max: 2000,
        minMessage: "min_length",
        maxMessage: 'max_length',
    )]
    private ?string $description = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message: "value_not_blank")]
    #[Assert\Length(
        min: 1,
        max: 255,
        minMessage: "min_length",
        maxMessage: 'max_length',
    )]
    private ?string $symbol = null;

    /**
     * @var Collection<int, ProductCategory>
     */
    #[ORM\ManyToMany(targetEntity: ProductCategory::class, inversedBy: 'products')]
    #[Assert\Count(min: 1, minMessage: "select_min")]
    private Collection $categories;

    /**
     * @var Collection<int, ProductImage>
     */
    #[ORM\OneToMany(targetEntity: ProductImage::class, mappedBy: 'productId', orphanRemoval: true)]
    private Collection $productImages;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->productImages = new ArrayCollection();
    }

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getAddDate(): ?\DateTimeImmutable
    {
        return $this->add_date;
    }

    public function setAddDate(\DateTimeImmutable $add_date): static
    {
        $this->add_date = $add_date;

        return $this;
    }

    public function getLastUpdate(): ?\DateTime
    {
        return $this->last_update;
    }

    public function setLastUpdate(\DateTime $last_update): static
    {
        $this->last_update = $last_update;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): static
    {
        $this->symbol = $symbol;

        return $this;
    }

    /**
     * @return Collection<int, ProductCategory>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(ProductCategory $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(ProductCategory $category): static
    {
        $this->categories->removeElement($category);

        return $this;
    }

    /**
     * @return Collection<int, ProductImage>
     */
    public function getProductImages(): Collection
    {
        return $this->productImages;
    }

    public function addProductImage(ProductImage $productImage): static
    {
        if (!$this->productImages->contains($productImage)) {
            $this->productImages->add($productImage);
            $productImage->setProductId($this);
        }

        return $this;
    }

    public function removeProductImage(ProductImage $productImage): static
    {
        if ($this->productImages->removeElement($productImage)) {
            // set the owning side to null (unless already changed)
            if ($productImage->getProductId() === $this) {
                $productImage->setProductId(null);
            }
        }

        return $this;
    }
}
