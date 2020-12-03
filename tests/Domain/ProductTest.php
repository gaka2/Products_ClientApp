<?php

namespace App\Tests\Domain;

use PHPUnit\Framework\TestCase;
use App\Domain\Product;
use App\Domain\Exception\InvalidArgumentException;

class ProductTest extends TestCase
{
    public function test_creating_product()
    {
        $id = 1;
        $name = 'Product';
        $amount = 20;
        $product = new Product($id, $name, $amount);

        self::assertEquals($id, $product->getId());
        self::assertEquals($name, $product->getName());
        self::assertEquals($amount, $product->getAmount());
    }

    public function test_throw_exception_when_creating_product_with_empty_name()
    {
        $this->expectException(InvalidArgumentException::class);
        new Product(1, '', 2);
    }

    public function test_throw_exception_when_creating_product_with_incorrect_amount()
    {
        $this->expectException(InvalidArgumentException::class);
        new Product(1, 'Product', -1);
    }
}
