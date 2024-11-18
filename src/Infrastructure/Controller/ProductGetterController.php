<?php

namespace App\Infrastructure\Controller;

use App\Application\UseCase\Product\GetProductsUseCaseRequest;
use App\Domain\Entity\Product\ProductCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Application\UseCase\Product\GetProductsUseCase;
use Symfony\Component\Routing\Annotation\Route;

final class ProductGetterController extends AbstractController
{
    public function __construct(private GetProductsUseCase $getProductsUseCase)
    {
    }

    #[Route('/products', methods: ['GET'])]
    public function getProducts(Request $request): JsonResponse
    {
        $category = $request->get('category');
        $priceLessThan = $request->get('priceLessThan');

        $getProductsUseCaseRequest = new GetProductsUseCaseRequest(
            category: $category,
            priceLessThan: $priceLessThan
        );

        $getProductsUseCaseResponse = $this->getProductsUseCase->execute($getProductsUseCaseRequest);

        if (!$getProductsUseCaseResponse->isValid()) {
            return new JsonResponse($getProductsUseCaseResponse->responseMessage, $getProductsUseCaseResponse->responseCode);
        }

        $productList = $this->formatProductList($getProductsUseCaseResponse->products);

        return new JsonResponse($productList);
    }

    public function formatProductList(ProductCollection $productCollection): array
    {
        $productList = [];

        foreach ($productCollection as $product) {
            $productPrice = $product->getPrice();
            $productList[] = [
                'sku' => $product->getSku()->value,
                'name' => $product->getName(),
                'category' => $product->getCategory()->getName(),
                'price' => [
                    'original' => $productPrice->getOriginal(),
                    'final' => $productPrice->getFinal(),
                    'discount_percentage' => $productPrice->getDiscountPercentage() ? $productPrice->getDiscountPercentage()->getFormattedValue() : null,
                    'currency' => $productPrice->getCurrency()->name
                ]

            ];
        }

        return $productList;
    }
}
