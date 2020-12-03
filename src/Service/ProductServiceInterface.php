<?php

namespace App\Service;

use App\Domain\Product;
use App\DataTransferObject\ProductDTO;

interface ProductServiceInterface {

    function create(ProductDTO $productDto): Product;
    
    function update(int $id, ProductDTO $productDto): Product;
    
    function get(int $id): Product;
    
    function delete(int $id): void;
    
    function getAll(): array;
	
    function getAvaivableProducts() : array;
    
    function getUnavailableProducts() : array;
    
    function getProductsWithAmountBiggerThan(int $amount) : array;

}