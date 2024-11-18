<?php

namespace App\Application\UseCase\Product;

use App\Domain\Entity\Product\ProductCollection;
use Symfony\Component\HttpFoundation\Response;

class GetProductsUseCaseResponse
{
    public function __construct(
        public int               $responseCode,
        public ProductCollection $products = new ProductCollection(),
        public string            $responseMessage = ''
    )
    {
    }

    public static function createValidResponse(ProductCollection $products): self
    {
        return new self(
            responseCode: Response::HTTP_OK,
            products: $products
        );
    }

    public static function createInvalidResponse(string $errorMessage): self
    {
        return new self(
            responseCode: Response::HTTP_BAD_REQUEST,
            responseMessage: $errorMessage
        );
    }

    public function isValid(): bool
    {
        return $this->responseCode === Response::HTTP_OK;
    }
}
