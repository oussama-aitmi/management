<?php


namespace App\Service;


use App\Entity\Product;
use App\Entity\Variation;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
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
     * @var EntityManagerInterface
     */
    private $em;

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
        EntityManagerInterface $em)
    {
        $this->security = $security;
        $this->validator = $validator;
        $this->variationService = $variationService;
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->em = $em;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function createProduct(array $data)
    {
        $entities = [];

        $this->validateProductAndRelatedResources($data, $entities);
        return $this->saveProductAndDataRelatedResources($entities);
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
        $productEntity = $this->productRepository->loadData($data);
        $productValidation = $this->getMessagesAndViolations($this->validator->validate($productEntity));

        if( !empty($productValidation) ) {
            $errors['product'] = $productValidation;
        }

        $entities['product'] = $productEntity;
    }

    private function saveProductAndDataRelatedResources($entities)
    {
        $user = $this->security->getUser();
        $product = $entities['product'];

        if(!$category = $this->categoryRepository->findOneBy(array('id'=> $product->getCategory(),'user' =>$user))){
            $this->renderFailureResponse(['The Category does not exist']);
        }

        $product->setCategory($category);
        $product->setUser($this->security->getUser());

        $this->productRepository->save($product);
        $this->saveRelatedResources($product, $entities);

        return $product;
    }

    /**
     * @param Product $product
     * @param         $entities
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function saveRelatedResources(Product $product, $entities)
    {
        if (isset($entities['variations'])) {
            $variations = $this->variationService->variationRepository->findBy(["product" => $product->getId()]);

            if($variations instanceof Variation){
                foreach ($variations as $variation) {
                    $this->em->remove($variation);
                }

                $this->em->flush();
            }

            foreach ($entities['variations'] as $variation) {
                $product->addVariation($variation);
                $this->em->persist($product);
            }

            $this->em->flush();
        }
    }


    public function updateProduct($product, array $data)
    {
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