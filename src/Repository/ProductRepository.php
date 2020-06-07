<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @param Product $product
     */
    public function save(Product $product): void
    {
        $this->_em->persist($product);
        $this->_em->flush();
    }

    /**
     * @param Product $product
     */
    public function delete(Product $product): void
    {
        $this->_em->remove($product);
        $this->_em->flush();
    }

    public function loadData($data)
    {
        if( isset($data['updateId'])){
            $updateId = $data['updateId'];
            if(empty($updateId) || !is_numeric($updateId) || !$product = $this->findOneBy(array("id" => $updateId))){
                throw new NotFoundHttpException("Error loading product Id !");
            }
        } else {
            $product = new Product();
        }

        return $product->loadData($data);
    }

}
