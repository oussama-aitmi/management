<?php

namespace App\Controller;

use App\Entity\Product;
use FOS\RestBundle\View\View;
use App\Service\ProductService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("api", name="api_")
 */
class ProductController extends BaseController
{

    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @Rest\Post("/product", name="post_product")
     * @param Request  $request
     * @return View
     * @throws \App\Response\ApiResponseException
     *
     * @Rest\View(serializerGroups={"public"}, serializerEnableMaxDepthChecks=1, StatusCode = 201)
     * @IsGranted("ROLE_MANAGE")
     */
    public function postProduct(Request $request): View
    {
        return $this->view($this->productService->saveProduct(
            $request->request->all(),
            $request->files->all()
        ), Response::HTTP_CREATED);
    }

    /**
     * @Rest\Put("/product/{id}", name="put_product")
     * @param Request $request
     * @param Product $product #Need for IsGranted
     * @param int     $id
     * @return View
     * @throws \App\Response\ApiResponseException
     *
     * @Rest\View(serializerGroups={"public"}, StatusCode = 200)
     * @IsGranted("PRODUCT_MANAGE", subject="product")
     */
    public function putProduct(Request $request, Product $product, $id): View
    {
        $data = $request->request->all();
        $data['updateId'] = $id;

        return $this->view($this->productService->saveProduct($data, $request->files->all()), Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/products", name="get_products")
     * @param ParamFetcherInterface $paramFetcher
     * @param PaginatorInterface    $paginator
     * @Rest\QueryParam(
     *     name="keyword",
     *     requirements="[a-zA-Z0-9]",
     *     nullable=true,
     *     description="The keyword to search for."
     * )
     * @Rest\QueryParam(
     *     name="page",
     *     requirements="[a-zA-Z0-9]",
     *     default="1",
     *     description="The pagination offset"
     * )
     * @Rest\View(serializerGroups={"public"}, StatusCode = 200)
     * @return View
     */
    public function showProducts(ParamFetcherInterface $paramFetcher, PaginatorInterface $paginator): View
    {
        $queryBuilder = $this->getDoctrine()
            ->getRepository(Product::class)
            ->getWithSearchQueryBuilder($paramFetcher->get('keyword')
            );

        return $this->view(
            $paginator->paginate(
            $queryBuilder,
            (int) $paramFetcher->get('page'),
            10)
            , Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/product/{id}", name="get_product")
     *
     * @return View
     * @Rest\View(serializerGroups={"public"}, StatusCode = 200)
     */
    public function showProduct(Product $product): View
    {
        return $this->view($product);
    }

}
