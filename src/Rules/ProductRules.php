<?php

namespace App\Rules;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

class ProductRules extends AbstractRulesService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param null $categoryId
     * @return Category|object|null
     */
    public function category($categoryId = null)
    {
        if (is_scalar($categoryId)) {
            $category = $this->em
                ->getRepository(Category::class)
                ->find((int) $categoryId);

            return $category ?? new Category();
        }

        return $categoryId;
    }
}
