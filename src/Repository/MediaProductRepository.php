<?php

namespace App\Repository;

use App\Entity\MediaProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MediaProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method MediaProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method MediaProduct[]    findAll()
 * @method MediaProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MediaProduct::class);
    }
}
