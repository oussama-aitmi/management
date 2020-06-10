<?php

namespace App\Tests;

use App\DataFixtures\CategoryFixtures;
use App\DataFixtures\UserFixture;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Service\CategoryService;
use App\Service\MediaProductService;
use App\Service\ProductService;
use App\Service\VariationService;
use App\Tests\DataFixtures\FakerTrait;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductServiceTest extends WebTestCase
{
    use FakerTrait;
    use FixturesTrait;

    public function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $this->loadFixtures([UserFixture::class, CategoryFixtures::class]);
    }

    public function testSaveProduct(): void
    {
        $user = self::$container->get(UserRepository::class)->find(1);
        $category = self::$container->get(CategoryRepository::class)->find(1);
        $productRepository = self::$container->get(ProductRepository::class);

        $data = $this->getProductFaker();
        $data['category'] = $category->getId();
        $data['user'] = $user->getId();

        $categoryService = $this->getMockBuilder(CategoryService::class)
            ->disableOriginalConstructor()
            ->getMock();
        $categoryService->expects($this->once())
            ->method('getCategoryById')
            ->willReturn($category);

        $security = $this->getMockBuilder(Security::class)
            ->disableOriginalConstructor()
            ->getMock();
        $security->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $validator = $this->getMockBuilder(ValidatorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $validator->expects($this->once())
            ->method('validate')
            ->willReturn([]);

        $variationService = $this->getMockBuilder(VariationService::class)->disableOriginalConstructor()->getMock();
        $mediaProductService = $this->getMockBuilder(MediaProductService::class)->disableOriginalConstructor()->getMock();

        $productService = new ProductService(
            $security,
            $productRepository,
            $validator,
            $categoryService,
            $variationService,
            $mediaProductService
        );

        $product =$productService->saveProduct($data);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals($data['name'], $product->getName());
        $this->assertEquals($data['reference'], $product->getReference());
        $this->assertEquals($data['status'], $product->getStatus());
        $this->assertEquals($data['statusStore'], $product->getStatusStore());
        $this->assertEquals($data['quantity'], $product->getQuantity());
        $this->assertEquals($data['basePrice'], $product->getBasePrice());
        $this->assertEquals($data['sellPrice'], $product->getSellPrice());
        $this->assertEquals($data['minimumSalesQuantity'], $product->getMinimumSalesQuantity());
        $this->assertEquals($data['maximumSalesQuantity'], $product->getMaximumSalesQuantity());
        $this->assertNotNull($product->getSlug());
    }



    /*public function SaveProduct(): void
    {
    $this->loadFixtures([UserFixture::class]);
    $this->loadFixtures([CategoryFixtures::class]);

    $user = self::$container->get(UserRepository::class)->findOneBy([]);
    $category = self::$container->get(CategoryRepository::class)->findOneBy([]);

    $data = $this->getProductFaker();
    $data['category'] = $category->getId();
    $data['user'] = $user->getId();

    $security = $this->getMockBuilder(Security::class)
        ->disableOriginalConstructor()
        ->getMock();

    $security->expects($this->once())
        ->method('getUser')
        ->willReturn($user);

    //$product = $this->productService->saveProduct($data);

    //dd($product);
    }*/


}
