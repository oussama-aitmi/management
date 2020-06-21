<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("api", name="profile_")
 */
class UserController extends BaseController
{

    /**
     * @var UserService
     */
    private $userService;

    function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @Route("/register", name="api_register", methods={"POST"})
     * @param User        $user
     * @return View
     * @ParamConverter(
     *     "user",
     *     converter="fos_rest.request_body",
     *     options={"deserializationContext"={"groups"={"allowPosted"}, "version"="1.0"}},
     * )
     * @Rest\View(serializerGroups={"public"}, StatusCode = 201)
     */
    public function register(User $user)
    {
        return $this->view($this->userService->registerUser($user), Response::HTTP_CREATED);
    }

    /**
     * @Route("/profile/edit", name="api_edit", methods={"PATCH"})
     * @param Request     $request
     * @return View
     * @Rest\View(serializerGroups={"public"}, StatusCode = 200)
     */
    public function editUser(Request $request)
    {
        return $this->view($this->userService->editUser($request->request->all()), Response::HTTP_OK);
    }

    /**
     * @Route("/profile/updatePassword", name="api_edit_password", methods={"PATCH"})
     * @param Request     $request
     * @return View
     * @Rest\View(serializerGroups={"public"}, StatusCode = 200)
     */
    public function updatePassword(Request $request)
    {
        return $this->view($this->userService->upgradePassword($request->request->all()), Response::HTTP_OK);
    }

    /**
     * @Route("/profile/user", name="api")
     * @Rest\View(serializerGroups={"public"}, StatusCode = 202)
     * @return View
     */
    public function LoggedInUser()
    {
        return $this->view([sprintf('Logged in as %s', $this->getUser())],Response::HTTP_ACCEPTED);
    }
}
