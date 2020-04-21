<?php

namespace App\Controller;

use App\Entity\User;
use App\Exception\ResourceValidationException;
use FOS\RestBundle\Controller\Annotations as Rest;
use Psr\Container\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\QueryParam;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use App\Service\AuthService;

use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * @Route("api", name="api_")
 */
class AuthController extends BaseController
{

    /**
     * @Rest\Post("/register", name="api_register")
     * @param User $user
     * @ParamConverter(
     *     "user",
     *     converter="fos_rest.request_body",
     *     options={"deserializationContext"={"groups"={"userCreate"}, "version"="1.0"}},
     * )
     * @Rest\View(serializerGroups={"public"}, StatusCode = 201)
     * @return View
     */
    public function register(User $user, AuthService $authService)
    {
        $res = $authService->register($user);
        return $this->view($res, Response::HTTP_CREATED);
    }


    /**
     * @Rest\Post("/checkEmailExist")
     * @Rest\View(StatusCode = 202)
     * @ParamConverter(
     *     "user",
     *     converter="fos_rest.request_body",
     *     options={
     *         "validator"={ "groups"="userChangeEmail" }
     *     }
     * )
     * @param User                    $user
     * @param ConstraintViolationList $violations
     */
    public function checkExistEmail(User $user, ConstraintViolationList $violations)
    {
    }


    /**
     * @Route("/api", name="api")
     * @return View
     */
    public function api(Request $request)
    {
        return $this->view([sprintf('Logged in as %s', $this->getUser()->getEmail())],
            Response::HTTP_CREATED)->setContext((new Context())->setGroups(['public'])
        );
    }

}
