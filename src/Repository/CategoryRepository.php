<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @param Category $category
     * @throws \Doctrine\ORM\ORMException
     */
    public function save(Category $category): void
    {
        $this->_em->persist($category);
        $this->_em->flush();
    }

    /**
     * @param Category $category
     * @throws \Doctrine\ORM\ORMException
     */
    public function delete(Category $category): void
    {
        $this->_em->remove($category);
        $this->_em->flush();
    }

    /**
     * @param $data
     * @return Category
     */
    public function loadData($data)
    {
        if(!isset($data['updateId'])){
            $category = new Category();
            return $category->loadData($data);
        }

        $updateId = $data['updateId'];

        if(!is_numeric($updateId) || !$category = $this->find($updateId)){
            throw new NotFoundHttpException("Error loading Category Id !");
        }

        return $category->loadData($data);
    }
}
