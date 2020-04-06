<?php

namespace App\Controller;

use App\Service\AuthService;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Psr\Container\ContainerInterface;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("api", name="api_")
 */
class AuthController extends AbstractFOSRestController
{
    /**
     * @var AuthService
     */
    private $authService;

    /**
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }


    /**
     * @Route("/register", name="api_register")
     * @param Request $request
     * @return View
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder, ValidatorInterface $validator)
    {
        $this->authService->Register($request);

        $em = $this->getDoctrine()->getManager();

        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $firstName = $request->request->get('first_name');

        $user = new User();
        $user->setEmail($email);
        $user->setFirstName($firstName);
        $user->setPassword($encoder->encodePassword($user, $password));

        $errors = $validator->validate($user);

        $errors = $this->validateResourceData($errors);

        //dd($errors);

        if (count($errors)) {
            return $this->view($errors, Response::HTTP_BAD_REQUEST);
        }

        $em->persist($user);
        $em->flush();

        return $this->view($user, Response::HTTP_CREATED)->setContext((new Context())->setGroups(['public']));
    }

    /**
     * @Route("/api", name="api")
     * @return View
     */
    public function api(Request $request)
    {
        //dd($request->headers);
        return $this->view([sprintf('Logged in as %s', $this->getUser()->getEmail())],
            Response::HTTP_CREATED)->setContext((new Context())->setGroups(['public'])
        );
    }



    public function validateResourceData($errors)
    {
        //$entity = $this->getFilter()->getFiltredEntity($data);

        //$errors = $this->validate($entity, $group);

        return [
            'errors' => (count($errors) > 0) ? $this->getViolationMessages($errors) : []
        ];
    }

    /**
     * Prepare validation returned errors messages
     *
     * @param ConstraintViolationList $errors
     * @return array
     */
    public function getViolationMessages(ConstraintViolationList $errors)
    {
        $messages = [];
        foreach ($errors as $error) {
            $messages[] = [
                'code'         => !empty($error->getConstraint()->payload)
                    ? $error->getConstraint()->payload : $error->getCode(),
                'message'      => $error->getMessage(),
                'field'        => $error->getPropertyPath(),
                'invalidValue' => $error->getInvalidValue()
            ];
        }
        return $messages;
    }
}
