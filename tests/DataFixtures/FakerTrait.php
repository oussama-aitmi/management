<?php
namespace App\Tests\DataFixtures;

use Faker\Factory;
use Faker\Generator;

trait FakerTrait
{
    /**
     * @param string $local
     *
     * @return Generator
     */
    public function getFaker($local = 'be_FR'): Generator
    {
        return Factory::create($local);
    }

    /**
     * @return array
     */
    public function getProductFaker()
    {
        $faker = $this->getFaker();

        return [
            'name'                  => $faker->text(50),
            'reference'             => $faker->randomNumber($nbDigits = NULL, $strict = false),
            'smallDescription'     => $faker->text(150),
            'status'                => $faker->randomElement(['DRAFT', 'PUBLISHED', 'DELETED']),
            'statusStore'          => $faker->boolean(25),
            'statusSiteWeb'       => $faker->boolean(25),
            'basePrice'            => $faker->numberBetween(2, 9999),
            'sellPrice'            => $faker->numberBetween(12, 999999),
            'minimumSalesQuantity'=> $faker->randomNumber(2),
            'maximumSalesQuantity'=> $faker->randomNumber(3),
            'quantity'              => $faker->randomNumber(5),
        ];
    }

    /**
     * @return array
     */
    public function getCategoryFaker()
    {
        $faker = $this->getFaker();
        return [
            'name'    => $faker->text(10),
        ];
    }

    /**
     * @return array
     */
    public function getVariationsFaker()
    {
        $faker = $this->getFaker();

        for ($c = 1; $c <= 5; $c++){
            $variation[] = [
                'value'            => $faker->text(50),
                'basePrice'        => $faker->numberBetween(2, 9999),
                'sellPrice'        => $faker->numberBetween(12, 999999),
                'quantity'         => $faker->randomNumber(5),
            ];
        }

        return $variation;
    }

    /**
     * @return array
     */
    public function getTagsFaker()
    {
        $faker = $this->getFaker();

        for ($c = 1; $c <= 5; $c++){
            $tags[] = [
                'name'    => $faker->text(10),
            ];
        }

        return $tags;
    }

}
