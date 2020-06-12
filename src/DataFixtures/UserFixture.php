<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends BaseFixture
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param ObjectManager $manager
     * @return User|void
     */
    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(1, 'main_users', function($i) use ($manager) {
            $user = new User();
            $user->setEmail(sprintf('convoi%d@management.com', $i));
            $user->setFirstName($this->faker->firstName);
            $user->setLastName($this->faker->lastName);
            $user->setRoles(['ROLE_ADMIN']);
            $user->setPassword($this->passwordEncoder->encodePassword($user,'management'));
            $manager->persist($user);
            return $user;
        });
        $manager->flush();
    }

}
