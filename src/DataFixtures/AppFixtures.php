<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordHasherInterface
     */
    private $hasher;

    public function __construct(UserPasswordHasherInterface  $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user= new Users();
        $user->setEmail("massidisemi@yahoo.fr");
        $password = $this->hasher->hashPassword($user, "123456789");
        $user->setRoles(["ROLE_ADMIN"]);
        $user->setPassword($password);
        $user->setName('reddy');
        $user->setLastName('massidi');
        $manager->persist($user);

        $manager->flush();
    }
}
