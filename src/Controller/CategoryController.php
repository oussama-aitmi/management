<?php

namespace App\Controller;

use App\Entity\Category;
use App\Service\CategoryService;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * @Route("api", name="api_")
 */
class CategoryController extends BaseController
{

    /**
     * @var CategoryService
     */
    private $categoryService;

    /**
     * CategoryController constructor.
     *
     * @param CategoryService $categoryService
     *
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * @Rest\Post("/category", name="post_category")
     * @param Category $category
     * @param Request  $request
     * @return View
     * @throws \App\Response\ApiResponseException
     * @ParamConverter(
     *     "category",
     *     converter="fos_rest.request_body",
     *     options={"deserializationContext"={"groups"={"allowPosted"}, "version"="1.0"}},
     * )
     * @Rest\View(serializerGroups={"public"},serializerEnableMaxDepthChecks=1, StatusCode = 201)
     */
    public function postCategory(Category $category,  Request $request): View
    {
        $data = $request->request->all();

        return $this->view($this->categoryService->addCategory($category->setUser($this->getUser()), $data));
    }

    /**
     * @Rest\Put("/category/{id}", name="put_category")
     * @param Request  $request
     * @param Category $category
     * @return View
     *
     * @Rest\View(serializerGroups={"public"}, StatusCode = 200)
     */
    public function putCategory(Request $request, Category $category): View
    {
        $data = $request->request->all();

        $category = $this->deserialize($request, Category::class,
            ['object_to_populate' => $category, 'groups' => 'allowPosted']
        );

        return $this->view($this->categoryService->updateCategory($category, $data), Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/category/{categoryId}", name="get_category")
     * @param int      $categoryId
     * @return View
     * @Rest\View(serializerGroups={"public"}, StatusCode = 200)
     */
    public function showCategory(int $categoryId) : View
    {
        return $this->view($this->categoryService->getCategory($this->getUser(), $categoryId), Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/category", name="get_categories")
     * @return View
     * @Rest\View(serializerGroups={"public"}, StatusCode = 200)
     */
    public function showCategories() : View
    {
        return $this->view($this->categoryService->getCategories($this->getUser()), Response::HTTP_OK);
    }

    /**
     * @Rest\Delete("/category/{categoryId}")
     * @param int $categoryId
     * @return View
     */
    public function deleteCategory(int $categoryId): View
    {
        return $this->view($this->categoryService->deleteCategory($categoryId), Response::HTTP_NO_CONTENT);
    }
}
