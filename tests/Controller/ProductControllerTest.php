<?php

namespace App\Tests\Controller;

use App\DataFixtures\CategoryFixtures;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class ProductControllerTest extends AbstractControllerTest
{
    protected $url = '/api/product';

    public function testCreateAction()
    {
        $this->loadFixtures([CategoryFixtures::class]);
        $category = self::$container->get(CategoryRepository::class)->find(1);

        $data = $this->getProductFaker();
        $data['category'] = $category->getId();

        $this->sendRequest('POST', '', $data);
        $result = $this->getDecodedResult();

        $this->basicAssertions($result, Response::HTTP_CREATED);
        $this->assertNotEmpty($result['id']);
        $this->assertNotEmpty($result['user']['id']);
        $this->assertNotEmpty($result['category']['id']);
    }

    public function testFailureCreateAction()
    {
        $data = $this->getProductFaker();
        $data['category'] = null;

        $this->sendRequest('POST', '', $data);
        $result = $this->getDecodedResult();

        $this->basicAssertions($result, Response::HTTP_BAD_REQUEST);
    }

    public function testCreateActionWithVariations()
    {
        $this->loadFixtures([CategoryFixtures::class]);
        $category = self::$container->get(CategoryRepository::class)->find(1);

        $data = $this->getProductFaker();
        $data['category'] = $category->getId();
        $data['variations'] = $this->getVariationsFaker();

        $this->sendRequest('POST', '', $data);
        $result = $this->getDecodedResult();

        $this->basicAssertions($result, Response::HTTP_CREATED);
        $this->assertNotEmpty($result['id']);
        $this->assertEquals($result['variations'][0]['value'], $data['variations'][0]['value']);
        $this->assertEquals($result['variations'][0]['basePrice'], $data['variations'][0]['basePrice']);
        $this->assertEquals($result['variations'][1]['value'], $data['variations'][1]['value']);
        $this->assertEquals($result['variations'][2]['value'], $data['variations'][2]['value']);
        $this->assertEquals($result['variations'][4]['value'], $data['variations'][4]['value']);
    }

    public function testCreateActionWithTags()
    {
        $this->loadFixtures([CategoryFixtures::class]);
        $category = self::$container->get(CategoryRepository::class)->find(1);

        $data = $this->getProductFaker();
        $data['category'] = $category->getId();
        $data['tags'] = $this->getTagsFaker();

        $this->sendRequest('POST', '', $data);
        $result = $this->getDecodedResult();


        $this->basicAssertions($result, Response::HTTP_CREATED);
        $this->assertNotEmpty($result['id']);
        $this->assertEquals($result['tags'][0]['name'], $data['tags'][0]['name']);
        $this->assertEquals($result['tags'][1]['name'], $data['tags'][1]['name']);
        $this->assertEquals($result['tags'][2]['name'], $data['tags'][2]['name']);
        $this->assertEquals($result['tags'][3]['name'], $data['tags'][3]['name']);
        $this->assertEquals($result['tags'][4]['name'], $data['tags'][4]['name']);
    }

    public function testCreateActionWithImages()
    {
        $this->loadFixtures([CategoryFixtures::class]);
        $category = self::$container->get(CategoryRepository::class)->find(1);

        $data = $this->getProductFaker();
        $data['category'] = $category->getId();
        $files['images'][] = $this->prepareFile();

        $this->sendRequest('POST', '', $data, $files);

        $result = $this->getDecodedResult();

        $this->basicAssertions($result, Response::HTTP_CREATED);
        $this->assertNotEmpty($result['id']);
        $this->assertNotEmpty($result['mediaProducts'][0]['id']);
        $this->assertNotEmpty($result['mediaProducts'][0]['path']);
    }

    private function prepareFile()
    {
        $dir = __DIR__.'/../DataFixtures/Media/';
        $filesPath = $dir.'test.png';
        $filesPathCopy = $dir.'example.copy.png';

        copy($filesPath, $filesPathCopy);

        return new UploadedFile($filesPathCopy, 'example.copy.png', 'image/png', null, true);
    }
}