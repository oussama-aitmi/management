<?php

namespace App\Controller;

use App\Service\CategoryService;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;


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
     * @param Request  $request
     * @return View
     * @Rest\View(serializerGroups={"public"},serializerEnableMaxDepthChecks=1, StatusCode = 201)
     */
    public function postCategory(Request $request): View
    {
        $data = $request->request->all();
        return $this->view($this->categoryService->saveCategory($data));
    }

    /**
     * @Rest\Put("/category/{id}", name="put_category")
     * @param Request $request
     * @param int     $id
     * @return View
     * @Rest\View(serializerGroups={"public"}, StatusCode = 200)
     */
    public function putCategory(Request $request, $id): View
    {
        $data = $request->request->all();
        $data['updateId'] = $id;

        return $this->view($this->categoryService->saveCategory($data), Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/category/{categoryId}", name="get_category")
     * @param int      $categoryId
     * @return View
     * @Rest\View(serializerGroups={"public", "subCategories"}, StatusCode = 200)
     */
    public function showCategory(int $categoryId) : View
    {
        return $this->view($this->categoryService->getCategory($categoryId), Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/category", name="get_categories")
     * @return View
     * @Rest\View(serializerGroups={"public", "subCategories"}, StatusCode = 200)
     */
    public function showCategories() : View
    {
        return $this->view($this->categoryService->getCategories(), Response::HTTP_OK);
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
