<?php


namespace App\Service;


use App\Entity\Product;
use Symfony\Component\Security\Core\Security;


class ProductService
{
    /**
     * @var Security
     */
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function addProduct(Product $product, array $data)
    {
        return $product;
    }

    public function updateProduct($product, array $data)
    {
        return $product;
    }

    public function getProduct(int $productId)
    {
        return [];
    }

    public function getProducts()
    {
        return [];
    }
}