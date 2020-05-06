<?php


namespace App\Service;


use App\Entity\Product;
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
        VariationService $variationService
        )
    {
        $this->security = $security;
        $this->validator = $validator;
        $this->variationService = $variationService;
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param array $data
     * @return mixed
     * @throws \App\Response\ApiResponseException
     */
    public function saveProduct(array $data)
    {
        $entities = [];

        $this->validateProductAndRelatedResources($data, $entities);
        return $this->saveProductAndRelatedResources($entities);
    }

    /**
     * @param $data
     * @param $entities
     * @throws \App\Response\ApiResponseException
     */
    private function validateProductAndRelatedResources($data, &$entities)
    {
        $errors = [];

        $this->validateProduct($data, $entities, $errors);
        $this->variationService->validateVariations($data, $entities, $errors);
        /*
         * Next Validations for Files and Tags
         */

        if ( \count( $errors ) ) {
            $dataError['form'] = $errors;
            $this->renderFailureResponse($dataError);
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
            $errors['product'] = $productValidation;
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

        $product->setCategory($category);

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