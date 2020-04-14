<?php

namespace App\Controller;

use App\Service\AuthService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationList;

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
    public function register(Request $request)
    {
        //throw new \Exception('Order you are looking for cannot be found.');
        return $this->view($this->authService->Register($request), Response::HTTP_CREATED);
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
            'error' => (count($errors) > 0) ? $this->getViolationMessages($errors) : []
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
                /*'code'         => !empty($error->getConstraint()->payload)
                    ? $error->getConstraint()->payload : $error->getCode(),*/
                'message'      => $error->getMessage(),
                'field'        => $error->getPropertyPath(),
                'invalidValue' => $error->getInvalidValue()
            ];
        }
        return $messages;
    }
}
