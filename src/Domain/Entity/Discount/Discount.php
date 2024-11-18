<?php

namespace App\Domain\Entity\Discount;

use App\Domain\Entity\Product\Product;
use App\Domain\ValueObject\DiscountPercentage;
use App\Domain\ValueObject\ProductReference;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'product_discount')]
class Discount
{
    #[ORM\Id]
    #[ORM\Column(name: 'sku', type: 'product_reference', unique: true, nullable: false)]
    private ProductReference $sku;

    #[ORM\Column(type: 'discount_percentage')]
    private DiscountPercentage $discount;

    #[ORM\OneToOne(targetEntity: Product::class, inversedBy: 'discount')]
    #[ORM\JoinColumn(name: 'sku', referencedColumnName: 'sku', onDelete: 'CASCADE')]
    private ?Product $product;

    public function __construct(Product $product, DiscountPercentage $discount)
    {
        $this->product = $product;
        $this->sku = $product->getSku();
        $this->discount = $discount;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function getDiscount(): DiscountPercentage
    {
        return $this->discount;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }
}
