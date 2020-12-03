<?php

namespace App\DataTransferObject;

use JMS\Serializer\Annotation\Type;

class ProductDTO {

    /**
     * @Type("string")
     */
    private $name;
    
    /**
     * @Type("integer")
     */
    private $amount;

    public function __construct(string $name, int $amount) {
        $this->name = $name;
        $this->amount = $amount;
    }
    
    public function getName(): ?string {
        return $this->name;
    }
    
    public function getAmount(): ?int {
        return $this->amount;
    }

}