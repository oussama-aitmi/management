<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends BaseFixture implements DependentFixtureInterface
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
            $user = $this->getRandomReference('main_users');
            $cat = self::$mainCategories[$i];
            $category = new Category();
            $category->setName($cat);
            $category->setUser($user);
            $manager->persist($category);

            for ($c = 1; $c <= 5; $c++) {
                $subCategory = new Category();
                $subCategory->setName(sprintf('sub category%d', $cat));
                $subCategory->setUser($user);
                $manager->persist($subCategory);

                $category->addSubCategory($subCategory);
            }

            return $category;
        });

        $manager->flush();
    }

    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return [
            UserFixture::class
        ];
    }
}
