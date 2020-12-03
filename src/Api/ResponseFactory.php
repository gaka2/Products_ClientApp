<?php

namespace App\Api;

use App\Entity\Product;
use App\Api\CorrectResponse;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;

class ResponseFactory {

    private $serializer;

    public function __construct(SerializerInterface $serializer) {
        $this->serializer = $serializer;
    }

    public function responseForDelete() : Response {
        return new Response('', Response::HTTP_NO_CONTENT);
    }

    public function responseForCreate($dataToSerialize) : CreatedResponse {
        $jsonString = $this->serializer->serialize($dataToSerialize, 'json');
        return new CreatedResponse($jsonString);
    }

    public function responseCorrect($dataToSerialize) : CorrectResponse {
        $jsonString = $this->serializer->serialize($dataToSerialize, 'json');
        return new CorrectResponse($jsonString);
    }
}