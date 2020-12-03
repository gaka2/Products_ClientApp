<?php

namespace App\Service\ExternalApiClient;

use App\Domain\Product;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\Exception\NoSuchIndexException;

use App\Service\ExternalApiClient\Excetpion\RuntimeException;
use App\DataTransferObject\ProductDTO;

class ExternalApiClient {

    private const API_URL = 'http://localhost/source_app/api/products';    
    private $client;

    public function __construct(HttpClientInterface $client) {
        $this->client = $client;
    }

    private function sendRequest(string $method, string $endpointPostfix, int $expectedStatusCode, ProductDTO $productDto = null) {

        if ($productDto !== null) {
            $response = $this->client->request($method, self::API_URL . $endpointPostfix,        
                [
                    'json' => ['name' => $productDto->getName(), 'amount' => $productDto->getAmount()],
                ]
            );
        } else {
            $response = $this->client->request($method, self::API_URL . $endpointPostfix);
        }

        $statusCode = $response->getstatusCode();
        if ($statusCode !== $expectedStatusCode) {
            throw new RuntimeException('External API returned wrong status code:' . $statusCode);
        }

        if ($statusCode === Response::HTTP_NO_CONTENT) {
            return;
        }

        $responseContent = $response->getContent();
        $dataFromApi = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        return $this->mapDataFromApi($dataFromApi);
   
    }

    public function getProduct(int $id): Product {
        return $this->sendRequest('GET', '/' . $id, Response::HTTP_OK);
    }

    public function createProduct(ProductDTO $productDto): Product {
        return $this->sendRequest('POST', '/', Response::HTTP_CREATED, $productDto);
    }

    public function updateProduct(int $id, ProductDTO $productDto): Product {
        return $this->sendRequest('PUT', '/' . $id, Response::HTTP_OK, $productDto);
    }
    
    public function deleteProduct(int $id): void {
        $this->sendRequest('DELETE', '/' . $id, Response::HTTP_NO_CONTENT);
    }
    
    public function getAllProducts() : array
    {
        return $this->sendRequest('GET', '/', Response::HTTP_OK);
    }

    private function mapDataFromApi(array $dataFromApi) {
        try {
            $propertyAccessor = PropertyAccess::createPropertyAccessorBuilder()->enableExceptionOnInvalidIndex()->getPropertyAccessor();
            
            $data = $propertyAccessor->getValue($dataFromApi, '[data]');

            if (array_key_exists(0, $data)) {
                return array_map( function ($item) {
                            return $this->mapSignleItemDataFromApi($item);
                            },
                            $data);
            } else {
                return $this->mapSignleItemDataFromApi($data);
            }
        } catch (NoSuchIndexException $e) {
            throw new \UnexpectedValueException('Invalid data passed to ' . __METHOD__ . ': ' . var_export($data, true));
        }
    }

    private function mapSignleItemDataFromApi(array $data): Product {
        try {
            $propertyAccessor = PropertyAccess::createPropertyAccessorBuilder()->enableExceptionOnInvalidIndex()->getPropertyAccessor();

            return new Product(
                $propertyAccessor->getValue($data, '[id]'),
                $propertyAccessor->getValue($data, '[name]'),
                $propertyAccessor->getValue($data, '[amount]')
            );

        } catch (NoSuchIndexException $e) {
            throw new \UnexpectedValueException('Invalid data passed to ' . __METHOD__ . ': ' . var_export($data, true));
        }
    }
}
