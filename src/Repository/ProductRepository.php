<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
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
     * @param string|null $keyword
     * @return QueryBuilder
     */
    public function getWithSearchQueryBuilder(?string $keyword): QueryBuilder
    {
        $qb = $this->createQueryBuilder('p');

        if ($keyword) {
            $qb->andWhere('p.name LIKE :keyword OR p.mallDescription LIKE :keyword')
                ->setParameter('keyword', '%' . $keyword . '%')
            ;
        }

        return $qb
            ->orderBy('p.createdAt', 'DESC');
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
        if(!isset($data['updateId'])){
            $product = new Product();
            return $product->loadData($data);
        }

        $updateId = $data['updateId'];

        if(!is_numeric($updateId) || !$product = $this->find($updateId)){
            throw new NotFoundHttpException("Error loading product Id !");
        }

        return $product->loadData($data);
    }
}
