<?php

namespace App\Domain\Entity\Category;

use App\Domain\ValueObject\DiscountPercentage;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'category')]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 100, unique: true)]
    private string $name;

    #[ORM\Column(type: 'discount_percentage', nullable: true)]
    private ?DiscountPercentage $discount;

    public function __construct(string $name, ?int $discount = null)
    {
        $this->name = $name;
        $this->discount = $discount;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDiscount(): ?DiscountPercentage
    {
        return $this->discount;
    }
}
