<?php


namespace App\Service;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class ProductService extends AbstractService
{
    /**
     * @var Security
     */
    private $security;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var VariationService
     */
    private $variationService;

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @var MediaProductService
     */
    private $mediaProductService;

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
        CategoryRepository $categoryRepository,
        VariationService $variationService,
        MediaProductService $mediaProductService
        )
    {
        $this->security = $security;
        $this->validator = $validator;
        $this->variationService = $variationService;
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->mediaProductService = $mediaProductService;
    }

    /**
     * @param array $data
     * @param array $files
     * @return mixed
     * @throws \App\Response\ApiResponseException
     */
    public function saveProduct(array $data, array $files)
    {
        $entities = [];

        $this->validateProductAndRelatedResources($data, $entities, $files);
        return $this->saveProductAndRelatedResources($entities);
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
        $this->mediaProductService->validateImages($data, $entities, $errors, $files);
        /*
         * Next Validations for Tags
         */

        if ( \count( $errors ) ) {
            $this->renderFailureResponse($errors);
        }
    }

    /**
     * @param $data
     * @param $entities
     * @param $errors
     */
    private function validateProduct($data, &$entities, &$errors)
    {
        $productEntity = $this->productRepository->loadData($data, $this->security->getUser());
        $productValidation = $this->getMessagesAndViolations($this->validator->validate($productEntity));

        if( !empty($productValidation) ) {
            $errors = $productValidation;
        }

        $entities['product'] = $productEntity;
    }

    private function saveProductAndRelatedResources($entities)
    {
        $product = $entities['product'];

        if(!$category = $this->categoryRepository->findOneBy(
            ['id'=> $product->getCategory(),'user' =>$this->security->getUser()])
        ){
            $this->renderFailureResponse(['The Category does not exist']);
        }

        //dd($entities['images']);
        $product->setCategory($category);
        $product->addMediaProduct($entities['images']);

        //dd($product->getMediaProducts());

        $this->productRepository->save($product);
        $this->variationService->saveVariations($product, $entities);

        return $product;
    }

    public function getProduct(int $productId)
    {
        return [];
    }

    public function getProducts()
    {
        return [];
    }

}