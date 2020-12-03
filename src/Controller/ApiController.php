<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ProductServiceInterface;
use App\Api\ResponseFactory;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\DataTransferObject\ProductDTO;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api/products", name="api.products.")
 */
class ApiController extends AbstractFOSRestController {

    private $productService;
    private $responseFactory;
    
    public function __construct(ProductServiceInterface $productService, ResponseFactory $responseFactory) {
        $this->productService = $productService;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @Rest\Post("/", name="add")
     * @ParamConverter("productDTO", converter="fos_rest.request_body")
     */
    public function addProduct(ProductDTO $productDTO) {
        $product = $this->productService->create($productDTO);
        return $this->responseFactory->responseForCreate($product);
    }

    /**
     * @Rest\Put("/{id}", name="edit", requirements={"id"="\d+"})
     * @ParamConverter("productDTO", converter="fos_rest.request_body")
     */
    public function editProduct(int $id, ProductDTO $productDTO) {
        $product = $this->productService->update($id, $productDTO);
        return $this->responseFactory->responseCorrect($product);
    }

    /**
     * @Rest\Delete("/{id}", name="delete", requirements={"id"="\d+"})
     */
    public function deleteProduct(int $id) {
        $product = $this->productService->delete($id);
        return $this->responseFactory->responseForDelete();
    }

    /**
     * @Rest\Get("/{id}", name="get", requirements={"id"="\d+"})
     */
    public function getProduct(int $id) {
        $product = $this->productService->get($id);
        return $this->responseFactory->responseCorrect($product);
    }

    /**
     * @Rest\Get("/", name="get_all")
     */
    public function getAllProducts(Request $request) {

        $amountBiggerThan = $request->query->getInt('amountBiggerThan');
        if ($amountBiggerThan === null) {
            $products = $this->productService->getAll();
        } else {
            $products = $this->productService->getProductsWithAmountBiggerThan($amountBiggerThan);            
        }
        return $this->responseFactory->responseCorrect($products);
    }

    /**
     * @Rest\Get("/avaivable", name="get_all_avaivable")
     */
    public function getAvailableProducts() {
        $products = $this->productService->getAvaivableProducts();
        return $this->responseFactory->responseCorrect($products);
    }

    /**
     * @Rest\Get("/unavaivable", name="get_all_unavaivable")
     */
    public function getUnavailableProducts() {
        $products = $this->productService->getUnavailableProducts();
        return $this->responseFactory->responseCorrect($products);
    }
}