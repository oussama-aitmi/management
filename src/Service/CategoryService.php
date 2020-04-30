<?php

namespace App\Service;


use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Response\ApiResponseException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CategoryService extends AbstractService{

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * CategoryService constructor.
     *
     * @param CategoryRepository $categoryRepository
     * @param ValidatorInterface $validator
     */
    public function __construct(CategoryRepository $categoryRepository, ValidatorInterface $validator) {
        $this->validator = $validator;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param Category $category
     * @param array    $data
     * @return Category
     * @throws ApiResponseException
     */
    public function addCategory(Category $category, $data): Category
    {
        if( !empty( $parent = $data['parent'] ) ){
            if (!$parentCategory = $this->categoryRepository->findOneBy(
                array('id'=> $parent,
                    "user" => $category->getUser()))
            ){
                $this->renderFailureResponse('The Category does not exist', Response::HTTP_NOT_FOUND);
            }
            $category->setParent($parentCategory);
        }

        if ( \count( $errors = $this->validator->validate($category) ) ) {
            $this->renderFailureResponse($this->normalizeViolations($errors));
        }

        $this->categoryRepository->save($category);

        return $category;
    }

    /**
     * @param Category $category
     * @param array    $data
     * @return Category
     * @throws ApiResponseException
     */
    public function updateCategory(Category $category, $data): Category
    {
        if( !empty( $parent = $data['parent'] ) ){
            if (!$parentCategory = $this->categoryRepository->findOneBy(
                array('id'=> $parent,
                    "user" => $category->getUser()))
            ){
                $this->renderFailureResponse('The Category does not exist', Response::HTTP_NOT_FOUND);
            }
            $category->setParent($parentCategory);
        }

        if ( \count( $errors = $this->validator->validate($category) )) {
            $this->renderFailureResponse($this->normalizeViolations($errors));
        }

        $this->categoryRepository->save($category);

        return $category;
    }

    /**
     * @param $userId
     * @return Category[] | null
     */
    public function getCategories($user)
    {
        return $this->categoryRepository->findBy(
            array("user" => $user),
            array('id'=> 'desc')
        );
    }

    /**
     * @param $user
     * @param $categoryId
     * @return Category
     */
    public function getCategory($user, $categoryId)
    {
        return $this->categoryRepository->findOneBy(
            array("user" => $user, "id" => $categoryId)
        );
    }

    /**
     * @param int $categoryId
     * @throws ApiResponseException
     */
    public function deleteCategory(int $categoryId)
    {
        $this->renderFailureResponse('No Delete Action for the moment!');

        $category = $this->categoryRepository->find($categoryId);
        if (!$category) {
            $this->renderFailureResponse('Category with id '.$categoryId.' does not exist!');
        }

        $this->categoryRepository->delete($category);
    }
}
