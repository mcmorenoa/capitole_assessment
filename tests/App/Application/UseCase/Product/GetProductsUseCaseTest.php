<?php

namespace App\Application\UseCase\Product;

use App\Domain\Entity\Category\Category;
use App\Domain\Entity\Product\Product;
use App\Domain\Entity\Product\ProductCollection;
use App\Domain\Repository\Category\CategoryRepositoryInterface;
use App\Domain\Repository\Product\ProductRepositoryInterface;
use App\Domain\Service\Product\DiscountCalculator;
use Doctrine\Common\Collections\Criteria;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GetProductsUseCaseTest extends TestCase
{
    private GetProductsUseCase $classUnderTest;
    private MockObject $productRepositoryMock;
    private MockObject $discountCalculatorMock;

    protected function setUp(): void
    {
        $this->productRepositoryMock = $this->createMock(ProductRepositoryInterface::class);
        $this->discountCalculatorMock = $this->createMock(DiscountCalculator::class);
        $this->categoryRepositoryMock = $this->createMock(CategoryRepositoryInterface::class);

        $this->classUnderTest = new GetProductsUseCase(
            $this->productRepositoryMock,
            $this->categoryRepositoryMock,
            $this->discountCalculatorMock
        );
    }

    public function testExecuteReturnsValidResponseWithDiscountedProducts(): void
    {
        $request = new GetProductsUseCaseRequest(
            category: 'boots',
            priceLessThan: 80000,
            limit: 5
        );

        $category = $this->createMock(Category::class);
        $this->categoryRepositoryMock
            ->expects($this->once())
            ->method('findOneByName')
            ->with('boots')
            ->willReturn($category);

        $products = new ProductCollection();
        $product = $this->createMock(Product::class);
        $products->add($product);

        $this->productRepositoryMock
            ->expects($this->once())
            ->method('match')
            ->with($this->isInstanceOf(Criteria::class))
            ->willReturn($products);

        $discountedProducts = new ProductCollection();
        $discountedProducts->add($product);

        $this->discountCalculatorMock
            ->expects($this->once())
            ->method('calculate')
            ->with($products)
            ->willReturn($discountedProducts);

        $response = $this->classUnderTest->execute($request);

        $this->assertInstanceOf(GetProductsUseCaseResponse::class, $response);
        $this->assertTrue($response->isValid());
        $this->assertEquals($discountedProducts, $response->products);
    }

    public function testExecuteReturnsValidResponseWithoutProducts(): void
    {
        $request = new GetProductsUseCaseRequest(
            category: null,
            priceLessThan: null,
            limit: 5
        );

        $this->productRepositoryMock
            ->expects($this->once())
            ->method('match')
            ->with($this->isInstanceOf(Criteria::class))
            ->willReturn(new ProductCollection());

        $this->discountCalculatorMock
            ->expects($this->never())
            ->method('calculate');

        $response = $this->classUnderTest->execute($request);

        $this->assertInstanceOf(GetProductsUseCaseResponse::class, $response);
        $this->assertTrue($response->isValid());
        $this->assertCount(0, $response->products);
    }

    public function testExecuteReturnsInvalidResponseOnException(): void
    {
        $request = new GetProductsUseCaseRequest(
            category: 'boots'
        );

        $this->categoryRepositoryMock
            ->expects($this->once())
            ->method('findOneByName')
            ->with('boots')
            ->willThrowException(new \Exception('This is a random exception'));

        $response = $this->classUnderTest->execute($request);

        $this->assertInstanceOf(GetProductsUseCaseResponse::class, $response);
        $this->assertFalse($response->isValid());
    }
}
