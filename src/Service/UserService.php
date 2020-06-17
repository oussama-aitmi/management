<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
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
     * @var Security
     */
    private $security;

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
        JWTTokenManagerInterface $JWTTokenManager,
        Security $security
    ){
        $this->validator = $validator;
        $this->encoder = $encoder;
        $this->userRepository = $userRepository;
        $this->JWTTokenManager = $JWTTokenManager;
        $this->security = $security;
    }

    /**
     * @param User  $user
     */
    public function registerUser(User $user)
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
     * @param $data
     * return boolean
     * @throws \App\Response\ApiResponseException
     */
    public function editUser($data)
    {
        $user = $this->security->getUser();

        $user->setFirstName($data['firstName']);
        $user->setLastName($data['lastName']);

        if (\count($errors = $this->validator->validate($user, null, ["editUser"]))) {
            $this->renderFailureResponse($this->getMessagesAndViolations($errors));
        }

        $this->userRepository->save($user);

        return $user;
    }

    /**
     * @param $data
     * @return boolean
     * @throws \App\Response\ApiResponseException
     */
    public function upgradePassword($data)
    {
        $user = $this->security->getUser();

        if (!$this->encoder->isPasswordValid($user, $data['currentPassword'])) {
            $this->renderFailureResponse('Ancien mot de passe est incorrect!');
        }

        $user->setPassword($data['newPassword']);

        if (\count($errors = $this->validator->validate($user, null, ['changePassword']))) {
            $this->renderFailureResponse($this->getMessagesAndViolations($errors));
        }

        $user->setPassword($this->encoder->encodePassword($user, $data['newPassword']));
        $this->userRepository->save($user);

        return true;
    }
}
