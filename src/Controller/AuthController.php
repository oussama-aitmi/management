<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\AuthService;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationList;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


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
     * @Route("/checkEmailExist", methods={"POST", "GET"})
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
     * @Route("/userConnected", methods={"POST", "GET"})
     * @Rest\View(serializerGroups={"public"}, StatusCode = 202)
     * @return View
     */
    public function LoggedInUser()
    {
        return $this->view($this->getUser());
    }

    /**
     * @Route("/api2", name="api")
     * @Rest\View(serializerGroups={"public"}, StatusCode = 202)
     * @return View
     */
    public function LoggedInUser2()
    {
        return $this->view([sprintf('Logged in as %s', $this->getUser())],Response::HTTP_ACCEPTED);
    }
}
