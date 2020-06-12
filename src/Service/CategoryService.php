<?php

namespace App\Service;


use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Response\ApiResponseException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
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
     * @var Security
     */
    private $security;

    /**
     * CategoryService constructor.
     *
     * @param CategoryRepository $categoryRepository
     * @param ValidatorInterface $validator
     * @param Security           $security
     */
    public function __construct(CategoryRepository $categoryRepository, ValidatorInterface $validator, Security $security) {
        $this->validator = $validator;
        $this->categoryRepository = $categoryRepository;
        $this->security = $security;
    }

    /**
     * @param Category $category
     * @param array    $data
     * @return Category
     * @throws ApiResponseException
     */
    public function saveCategory(Category $category, $data): Category
    {
        if (\count($errors = $this->validator->validate($category))) {
            $this->renderFailureResponse($this->normalizeViolations($errors));
        }

        if(!empty($parent = $data['parent'] )){
            if ($parentCategory = $this->getCategoryById($parent)){
                $category->setParent($parentCategory);
            }
        }

        $this->categoryRepository->save($category);

        return $category;
    }

    /**
     * @param int $id
     * @return Category|null
     * @throws ApiResponseException
     */
    public function getCategoryById(int $id)
    {
        if (!$category = $this->categoryRepository->find($id)) {
            $this->renderFailureResponse('Catégorie est invalide', Response::HTTP_NOT_FOUND);
        }

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
