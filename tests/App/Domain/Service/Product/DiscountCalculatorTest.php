<?php

namespace tests\App\Domain\Service;

use App\Domain\Entity\Category\Category;
use App\Domain\Entity\Discount\Discount;
use App\Domain\Entity\Product\Product;
use App\Domain\Entity\Product\ProductCollection;
use App\Domain\Service\Product\DiscountCalculator;
use App\Domain\ValueObject\DiscountPercentage;
use App\Domain\ValueObject\Price;
use PHPUnit\Framework\TestCase;

class DiscountCalculatorTest extends TestCase
{
    private DiscountCalculator $classUnderTest;

    protected function setUp(): void
    {
        $this->classUnderTest = new DiscountCalculator();
    }

    public function testCalculateWithCategoryDiscount(): void
    {
        $initialPrice = 10000;
        $expectedFinalPrice = 9000;

        $categoryDiscount = new DiscountPercentage(10);

        $category = $this->createMock(Category::class);
        $category
            ->method('getDiscount')
            ->willReturn($categoryDiscount);

        $price = $this->createMock(Price::class);
        $price
            ->method('getOriginal')
            ->willReturn($initialPrice);

        $price
            ->expects($this->once())
            ->method('applyDiscount')
            ->with($categoryDiscount)
            ->willReturnCallback(function () use ($price, $expectedFinalPrice) {
                $price->method('getFinal')->willReturn($expectedFinalPrice);
            });

        $product = $this->createMock(Product::class);
        $product
            ->method('getCategory')
            ->willReturn($category);
        $product
            ->method('getDiscount')
            ->willReturn(null);
        $product
            ->method('getPrice')
            ->willReturn($price);

        $productCollection = new ProductCollection();
        $productCollection->add($product);

        $result = $this->classUnderTest->calculate($productCollection);

        $this->assertCount(1, $result->items());
        $this->assertEquals($expectedFinalPrice, $product->getPrice()->getFinal());
    }

    public function testCalculateWithSkuDiscount(): void
    {
        $initialPrice = 20000;
        $expectedFinalPrice = 16000;

        $skuDiscount = new DiscountPercentage(20);

        $category = $this->createMock(Category::class);
        $category
            ->method('getDiscount')
            ->willReturn(null);

        $price = $this->createMock(Price::class);
        $price
            ->method('getOriginal')
            ->willReturn($initialPrice);

        $price
            ->expects($this->once())
            ->method('applyDiscount')
            ->with($skuDiscount)
            ->willReturnCallback(function () use ($price, $expectedFinalPrice) {
                $price->method('getFinal')->willReturn($expectedFinalPrice);
            });

        $product = $this->createMock(Product::class);

        $productDiscount = $this->createMock(Discount::class);
        $productDiscount
            ->method('getDiscount')
            ->willReturn($skuDiscount);

        $product
            ->method('getCategory')
            ->willReturn($category);
        $product
            ->method('getDiscount')
            ->willReturn($productDiscount);
        $product
            ->method('getPrice')
            ->willReturn($price);

        $productCollection = new ProductCollection();
        $productCollection->add($product);

        $result = $this->classUnderTest->calculate($productCollection);

        $this->assertCount(1, $result->items());
        $this->assertEquals($expectedFinalPrice, $product->getPrice()->getFinal());
    }

    public function testCalculateWithBothDiscountsWillApplyBiggestDiscount(): void
    {
        $initialPrice = 15000;
        $expectedFinalPrice = 12000;

        $categoryDiscount = new DiscountPercentage(15);
        $skuDiscount = new DiscountPercentage(20);

        $category = $this->createMock(Category::class);
        $category
            ->method('getDiscount')
            ->willReturn($categoryDiscount);

        $price = $this->createMock(Price::class);
        $price
            ->method('getOriginal')
            ->willReturn($initialPrice);
        $price
            ->expects($this->once())
            ->method('applyDiscount')
            ->with($skuDiscount)
            ->willReturnCallback(function () use ($price, $expectedFinalPrice) {
                $price->method('getFinal')->willReturn($expectedFinalPrice);
            });

        $product = $this->createMock(Product::class);

        $productDiscount = $this->createMock(Discount::class);
        $productDiscount
            ->method('getDiscount')
            ->willReturn($skuDiscount);

        $product
            ->method('getCategory')
            ->willReturn($category);
        $product
            ->method('getDiscount')
            ->willReturn($productDiscount);
        $product
            ->method('getPrice')
            ->willReturn($price);

        $productCollection = new ProductCollection();
        $productCollection->add($product);

        $result = $this->classUnderTest->calculate($productCollection);

        $this->assertCount(1, $result->items());
        $this->assertEquals($expectedFinalPrice, $product->getPrice()->getFinal());
    }
}
