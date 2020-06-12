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
    public function __construct(CategoryRepository $categoryRepository, ValidatorInterface $validator, Security $security){
        $this->validator = $validator;
        $this->categoryRepository = $categoryRepository;
        $this->security = $security;
    }

    /**
     * @param array    $data
     * @return Category
     * @throws ApiResponseException
     */
    public function saveCategory($data): Category
    {
        $category = $this->categoryRepository->loadData($data);
        $category->setUser($this->security->getUser());

        if (\count($errors = $this->validator->validate($category))) {
            $this->renderFailureResponse($this->getMessagesAndViolations($errors));
        }

        if(isset($data['parent']) && is_numeric($parent = $data['parent'])){
            $category->setParent($this->getCategoryById($parent));
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
     * @param $user
     * @param $categoryId
     * @return Category
     */
    public function getCategory($categoryId)
    {
        return $this->categoryRepository->find($categoryId);
    }

    /**
     * @param $userId
     * @return Category[] | null
     */
    public function getCategories()
    {
        return $this->categoryRepository->findBy([],['id'=> 'desc']);
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
