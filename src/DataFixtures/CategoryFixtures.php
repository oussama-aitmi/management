<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends BaseFixture
{
    private static $mainCategories = [
        'Ordinateur et bureau',
        'Beauté et santé',
        'Bijoux et montres',
        'Mode homme',
        'Électronique',
        'Téléphones et accessoires',
        'Chaussures'
    ];

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(6, 'main_category', function($i) use ($manager) {
            $category = new Category();
            $category->setName(self::$mainCategories[$i]);
            $manager->persist($category);

            for ($c = 1; $c <= 5; $c++) {
                $subCategory = new Category();
                $subCategory->setName(sprintf('sub category%d', $c));
                $manager->persist($subCategory);

                $category->addSubCategory($subCategory);
            }

            return $category;
        });

        $manager->flush();
    }
}
