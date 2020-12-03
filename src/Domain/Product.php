<?php

namespace App\Domain;

use App\Domain\Exception\InvalidArgumentException;

class Product {

    private $id;
    private $name;
    private $amount;

    public function __construct(?int $id, ?string $name, ?int $amount) {
        
        if ($name === null || $name === '') {
            throw new InvalidArgumentException('Product name can not be empty');
        }
        
        if ($amount === null || $amount < 0) {
            throw new InvalidArgumentException('Product amount must be non-negative number');
        }
        
        $this->id = $id;
        $this->name = $name;
        $this->amount = $amount;
    }
    
    public function getId(): int {
        return $this->id;
    }
    
    public function getName(): string {
        return $this->name;
    }
    
    public function getAmount(): int {
        return $this->amount;
    }
}