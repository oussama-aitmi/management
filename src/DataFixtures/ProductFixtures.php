<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ProductFixtures extends BaseFixture implements DependentFixtureInterface
{

    private static $productImages = [
        'product1.jpg',
        'product2.jpg',
        'product3.jpg',
        'product4.jpg',
        'product5.jpg'
    ];

    private static $productName = [
        'Xiaomi Mi Band 4 Smart Miband',
        'Bracelet hommes multicouches',
        'Samsung TV Smart 52 Qled 2020',
        'bracelets en cuir fermoir magnétique',
        'bijoux de luxe cristaux éxquis de Swarovski',
        'montre intelligente 44MM',
        'Redmi Note 8 Pro',
        'Notbad11',
        'Sac cuire',
        'chargeur voiture ORA',
        'Imprimente Laser SSE'
        ];


    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(10, 'main_product', function($count) use ($manager) {
            $product = new product();

            $product->setName(self::$productName[$count])
                    ->setStatus(true)
                    ->setBasePrice($this->faker->numberBetween(50, 500))
                    ->setDescription($this->faker->text(50))
                    ->setImageURL($this->faker->randomElement(self::$productImages))
                    ->setSpecialPrice($this->faker->numberBetween(50, 500))
                    ->setUser($this->getRandomReference('main_users'))
                    ->setCategory($this->getRandomReference('main_category'));

            return $product;
        });

        $manager->flush();
    }

    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return [
            UserFixture::class,
            CategoryFixtures::class
        ];
    }
}
