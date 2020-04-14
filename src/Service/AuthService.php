<?php

namespace App\Service;


use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthService extends AbstractService{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * AuthService constructor.
     *
     * @param UserPasswordEncoderInterface $encoder
     * @param ValidatorInterface           $validator
     */
    public function __construct(UserPasswordEncoderInterface $encoder, ValidatorInterface $validator)
    {
        $this->encoder = $encoder;
        $this->validator = $validator;
    }


    /**
     * @param $request
     * @return User
     * @throws \App\Exception\ApiResponseException
     */
    public function Register($request)
    {
        //$this->renderNotFoundResponse('Order you are looking for cannot be found.');

        if (!$request) {
            die('ddd');
            //$this->renderNotFoundResponse();
        }

        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $firstName = $request->request->get('first_name');
        //die('eee');

        $user = new User();
        $user->setEmail($email);
        $user->setFirstName($firstName);
        $user->setPassword($this->encoder->encodePassword($user, $password));

        $errors = $this->validator->validate($user);

        //$errors = $this->validateData($errors);

        if (\count($errors)) {
            $this->renderBadRequestResponse($errors);
            //return $this->view($errors, Response::HTTP_BAD_REQUEST);
        }

        $this->save($user);

        return $user;
    }

}
