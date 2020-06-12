<?php

namespace App\Service;


use App\Entity\User;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
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
     * @var JWTTokenManagerInterface
     */
    private  $JWTTokenManager;

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
        UserPasswordEncoderInterface $encoder,
        JWTTokenManagerInterface $JWTTokenManager
    ){
        $this->validator = $validator;
        $this->encoder = $encoder;
        $this->userRepository = $userRepository;
        $this->JWTTokenManager = $JWTTokenManager;
    }

    /**
     * @param User  $user
     */
    public function register(User $user)
    {
        if (\count($errors = $this->validator->validate($user))) {
            $this->renderFailureResponse($this->getMessagesAndViolations($errors));
        }

        $user->setPassword($this->encoder->encodePassword($user, $user->getPassword()));
        $this->userRepository->save($user);

        return [
            'token' => $this->JWTTokenManager->create($user)
        ];
    }

    /**
     * @param User $user
     */
    public function updatePassword(User $user)
    {

    }

}
