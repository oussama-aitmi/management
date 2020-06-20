<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class ProductService extends AbstractService
{

    private $security;

    private $productRepository;

    private $validator;

    private $variationService;

    private $mediaProductService;

    private $categoryService;

    /**
     * ProductService constructor.
     *
     * @param Security           $security
     * @param ProductRepository  $productRepository
     * @param ValidatorInterface $validator
     */
    public function __construct(
        Security $security,
        ProductRepository $productRepository,
        ValidatorInterface $validator,
        CategoryService $categoryService,
        VariationService $variationService,
        MediaProductService $mediaProductService
    )
    {
        $this->security = $security;
        $this->validator = $validator;
        $this->variationService = $variationService;
        $this->productRepository = $productRepository;
        $this->mediaProductService = $mediaProductService;
        $this->categoryService = $categoryService;
    }

    /**
     * @param array $data
     * @param array $files
     * @return mixed
     * @throws \App\Response\ApiResponseException
     */
    public function saveProduct(array $data, array $files = null)
    {
        $entities = [];

        $this->validateProductAndRelatedResources($data, $entities, $files);
        return $this->saveProductAndRelatedResources($entities, $data);
    }

    /**
     * @param $data
     * @param $entities
     * @throws \App\Response\ApiResponseException
     */
    private function validateProductAndRelatedResources($data, &$entities, $files)
    {
        $errors = [];

        $this->validateProduct($data, $entities, $errors);
        $this->variationService->validateVariations($data, $entities, $errors);
        $this->mediaProductService->validateImages($entities, $errors, $files);
        /*
         * Next Validation for Tags
         */

        if (\count( $errors )) {
            $outPut['errors'] = $errors;
            $this->renderFailureResponse($outPut);
        }
    }

    /**
     * @param $data
     * @param $entities
     * @param $errors
     */
    private function validateProduct($data, &$entities, &$errors)
    {
        $productEntity = $this->productRepository->loadData($data);
        $productValidation = $this->getDetailsViolations($this->validator->validate($productEntity));

        if(!empty($productValidation)) {
            $errors['product'] = $productValidation;
        }

        $entities['product'] = $productEntity;
    }

    /**
     * @param $entities
     * @param $data
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function saveProductAndRelatedResources($entities, $data)
    {
         /** @var Product $product */
        $product = $entities['product'];

        /*
         * Prepare Product && Assign dependencies
         */
        $product->setCategory($this->categoryService->getCategoryById($data['category']));

        if (isset($entities['images'])) {
            foreach ($entities['images'] as $mediaProduct) {
                $product->addMediaProduct($mediaProduct);
            }
        }

        /*
        * Update case : We keep the owner of the product if the administrator who is doing the update
        */
        if(!isset($data['updateId'])) {
            $product->setUser($this->security->getUser());
        }

        /*
         * Save All
         */
        $this->productRepository->save($product);
        $this->variationService->saveVariations($product, $entities);

        return $product;
    }

}