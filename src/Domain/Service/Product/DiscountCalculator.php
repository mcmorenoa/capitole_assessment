<?php

namespace App\Domain\Service\Product;

use App\Domain\Entity\Product\ProductCollection;

class DiscountCalculator
{
    public function calculate(ProductCollection $productCollection): ProductCollection
    {
        $updatedProductCollection = new ProductCollection();

        foreach ($productCollection->items() as $product) {
            $discountPercentage = null;

            $categoryDiscountPercentage = $product->getCategory()->getDiscount();
            $skuDiscountPercentage = $product->getDiscount()?->getDiscount();

            if (!$categoryDiscountPercentage && !$skuDiscountPercentage) {
                $updatedProductCollection->add($product);
                continue;
            } else {
                $discountPercentage = $skuDiscountPercentage ?: $categoryDiscountPercentage;

                if ($categoryDiscountPercentage && $skuDiscountPercentage) {
                    $discountPercentage = $categoryDiscountPercentage->getValue() > $skuDiscountPercentage->getValue()
                        ? $categoryDiscountPercentage
                        : $skuDiscountPercentage;
                }
            }

            $product->getPrice()->applyDiscount($discountPercentage);
            $updatedProductCollection->add($product);
        }

        return $updatedProductCollection;
    }
}
