<?php

namespace App\Service;


use App\Entity\User;
use Doctrine\ORM\EntityManager;

class AuthService extends AbstractService{


    /**
     * @param User          $user
     * @param EntityManager $manager
     * @return \Symfony\Component\Validator\ConstraintViolationListInterface | User
     */
    public function register(User $user)
    {
        $this->validate($user);

        $user->setPassword($this->encoder->encodePassword($user, $user->getPassword()));

        $this->save($user);

        return $user;
    }

    /**
     * @param User $user
     */
    public function changePassword(User $user)
    {

    }

}
