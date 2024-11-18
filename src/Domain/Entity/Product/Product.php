<?php

namespace App\Domain\Entity\Product;

use App\Domain\Entity\Category\Category;
use App\Domain\Entity\Discount\Discount;
use App\Domain\ValueObject\Price;
use App\Domain\ValueObject\ProductReference;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'product')]
#[ORM\Entity(repositoryClass: 'App\Infrastructure\Persistence\Doctrine\Repository\Product\ProductRepository')]
class Product
{
    #[ORM\Id]
    #[ORM\Column(name: 'sku', type: 'product_reference', unique: true, nullable: false)]
    private ProductReference $sku;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $name;

    #[ORM\ManyToOne(targetEntity: Category::class, fetch: 'EAGER')]
    #[ORM\JoinColumn(name: 'category_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Category $category;

    #[ORM\Column(type: 'price', nullable: false)]
    private Price $price;

    #[ORM\OneToOne(targetEntity: Discount::class, mappedBy: 'product', cascade: ['persist', 'remove'])]
    private ?Discount $discount = null;

    public function __construct(ProductReference $sku, string $name, Category $category, Price $price)
    {
        $this->sku = $sku;
        $this->name = $name;
        $this->category = $category;
        $this->price = $price;
    }

    public function getSku(): ProductReference
    {
        return $this->sku;
    }

    public function setSku(ProductReference $sku): void
    {
        $this->sku = $sku;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }

    public function getPrice(): Price
    {
        return $this->price;
    }

    public function setPrice(Price $price): void
    {
        $this->price = $price;
    }

    public function getDiscount(): ?Discount
    {
        return $this->discount;
    }
}
