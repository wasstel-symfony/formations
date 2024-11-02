<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const ADMIN_USER = 'ADMIN';
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('admin')
            ->setEmail('admin@admin.com')
            ->setVerified(true)
            ->setPassword($this->passwordHasher->hashPassword($user, 'admin'))
            ->setApiToken('admin_token')
            ->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);
        $this->addReference(self::ADMIN_USER, $user);
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setUsername('user' . $i)
                ->setEmail('user' . $i . '@user.com')
                ->setVerified(true)
                ->setPassword($this->passwordHasher->hashPassword($user, 'user' . $i))
                ->setApiToken('usertoken' . $i)
                ->setRoles(['ROLE_USER']);
            $manager->persist($user);
            $this->addReference('user' . $i, $user);
        }
        $manager->flush();
    }


}
