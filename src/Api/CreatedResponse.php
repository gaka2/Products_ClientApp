<?php

namespace App\Api;

use Symfony\Component\HttpFoundation\Response;

class CreatedResponse extends AbstractResponse {

    public function __construct(string $jsonData) {
        parent::__construct('ok', '', $jsonData, Response::HTTP_CREATED);
    }
}