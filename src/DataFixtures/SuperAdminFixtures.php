<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SuperAdminFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $superAdmin =$this->createSuperAdmin();
        $manager->persist($superAdmin);
        $manager->flush();
    }

    private function createSuperAdmin(): User
    {
        $superAdmin = new User;

        $superAdmin->setFirstName("Marion");
        $superAdmin->setLastName("Dellupo");
        $superAdmin->setEmail("canap-plus@gmail.com");
        $superAdmin->setRoles(["ROLE_SUPER_ADMIN", "ROLE_ADMIN", "ROLE_USER"]);
        $superAdmin->setVerified(true);
        $superAdmin->setPhone("0141414141");
        $superAdmin->setVerifiedAt( new DateTimeImmutable());
        $superAdmin->setAdress("1 rue de Paris");
        $superAdmin->setCity("Paris");
        $superAdmin->setCp(75008);

        $passwordSuperAdmin = $this->hasher->hashPassword($superAdmin, "2Y6zGjqp?!");
        $superAdmin->setPassword($passwordSuperAdmin);
        
        return $superAdmin;
    }
}
