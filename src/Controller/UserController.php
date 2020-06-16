<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationList;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("api", name="api_")
 */
class UserController extends BaseController
{

    /**
     * @Rest\Post("/register", name="api_register")
     * @param User        $user
     * @param UserService $authService
     * @return View
     * @ParamConverter(
     *     "user",
     *     converter="fos_rest.request_body",
     *     options={"deserializationContext"={"groups"={"userCreate"}, "version"="1.0"}},
     * )
     * @Rest\View(serializerGroups={"public"}, StatusCode = 201)
     */
    public function register(User $user, UserService $authService)
    {
        return $this->view($authService->register($user), Response::HTTP_CREATED);
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
     * @param User $user
     */
    public function updatePassword(User $user)
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
     * @Route("/api", name="api")
     * @Rest\View(serializerGroups={"public"}, StatusCode = 202)
     * @return View
     */
    public function api()
    {
        return $this->view([sprintf('Logged in as %s', $this->getUser())],Response::HTTP_ACCEPTED);
    }
}
