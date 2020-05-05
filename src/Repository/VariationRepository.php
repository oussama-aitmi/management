<?php

namespace App\Repository;

use App\Entity\Variation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Variation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Variation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Variation[]    findAll()
 * @method Variation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VariationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Variation::class);
    }

    public function loadData($data)
    {
        $variation = new Variation();
        return $variation->loadData($data);
    }

}
