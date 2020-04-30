<?php

namespace App\Service;


use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService extends AbstractService{

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserPasswordEncoderInterface
     */
    protected $encoder;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * AuthService constructor.
     *
     * @param UserRepository               $userRepository
     * @param ValidatorInterface           $validator
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(
        UserRepository $userRepository,
        ValidatorInterface $validator,
        UserPasswordEncoderInterface $encoder
    ){
        $this->validator = $validator;
        $this->encoder = $encoder;
        $this->userRepository = $userRepository;
    }

    /**
     * @param User  $user
     */
    public function register(User $user)
    {
        if (\count($errors = $this->validator->validate($user))) {
            $this->renderFailureResponse($this->normalizeViolations($errors));
        }

        $user->setPassword($this->encoder->encodePassword($user, $user->getPassword()));

        $this->userRepository->save($user);

        return $user;
    }

    /**
     * @param User $user
     */
    public function updatePassword(User $user)
    {

    }

}
