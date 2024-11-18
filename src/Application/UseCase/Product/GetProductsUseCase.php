<?php

namespace App\Application\UseCase\Product;

use App\Domain\Repository\Category\CategoryRepositoryInterface;
use App\Domain\Repository\Product\ProductRepositoryInterface;
use App\Domain\Service\Product\DiscountCalculator;
use Doctrine\Common\Collections\Criteria;

class GetProductsUseCase
{
    public function __construct(
        private ProductRepositoryInterface  $productRepository,
        private CategoryRepositoryInterface $categoryRepository,
        private DiscountCalculator          $discountCalculator
    )
    {
    }

    public function execute(GetProductsUseCaseRequest $getProductsUseCaseRequest)
    {
        try {
            $searchCriteria = $this->buildCriteria($getProductsUseCaseRequest);

            $products = $this->productRepository->match($searchCriteria);

            if (!$products->isEmpty()) {
                $products = $this->discountCalculator->calculate($products);
            }

            return GetProductsUseCaseResponse::createValidResponse($products);
        } catch (\Throwable $exception) {
            return GetProductsUseCaseResponse::createInvalidResponse($exception->getMessage());
        }
    }

    private function buildCriteria(GetProductsUseCaseRequest $request): Criteria
    {
        $criteria = Criteria::create();

        $category = $request->category ? $this->categoryRepository->findOneByName($request->category) : null;
        $price = $request->priceLessThan;
        $limit = $request->limit;

        if ($category) {
            $criteria->andWhere(Criteria::expr()->eq('category', $category));
        }

        if ($price) {
            $criteria->andWhere(Criteria::expr()->lte('price', $price));
        }

        if ($limit) {
            $criteria->setMaxResults($limit);
        }

        return $criteria;
    }
}
