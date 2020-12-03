<?php

namespace App\Service;

use App\Domain\Product;
use App\DataTransferObject\ProductDTO;

use App\Service\ExternalApiClient\ExternalApiClient;

class ProductService implements ProductServiceInterface {
    
    private $externalApiClient;
    
    public function __construct(ExternalApiClient $externalApiClient) {
        $this->externalApiClient = $externalApiClient;
    }
    
    public function create(ProductDTO $productDto): Product {
        return $this->externalApiClient->createProduct($productDto);
    }
    
    public function update(int $id, ProductDTO $productDto): Product {
        return $this->externalApiClient->updateProduct($id, $productDto);
    }
    
    public function get(int $id): Product {
        return $this->externalApiClient->getProduct($id);
    }
    
    public function delete(int $id): void {
        $this->externalApiClient->deleteProduct($id);
    }
    
    public function getAll(): array {
        return $this->externalApiClient->getAllProducts();
    }

    public function getAvaivableProducts() : array {
        return $this->getProductsWithAmountBiggerThan(0);
    }
    
    public function getUnavailableProducts() : array {
        return array_values(array_filter($this->getAll(), function ($product) {
            return $product->getAmount() === 0;
        }));
    }
    
    public function getProductsWithAmountBiggerThan(int $amount) : array {
        return array_values(array_filter($this->getAll(), function ($product) use ($amount) {
            return $product->getAmount() > $amount;
        }));
    }
}