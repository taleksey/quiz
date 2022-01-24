<?php

namespace App\Infractructure\DataFixtures;

use App\Domain\Customer\Entity\Customer;
use App\Infractructure\ValueObject\TestCustomer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityCustomerFixtures extends Fixture
{
    public const SECURITY_ADMIN_USER = 'admin';

    private UserPasswordHasherInterface $hasher;
    private TestCustomer $customer;

    public function __construct(UserPasswordHasherInterface $hasher, TestCustomer $customer)
    {
        $this->hasher = $hasher;
        $this->customer = $customer;
    }

    public function load(ObjectManager $manager): void
    {
        $userAdmin = new Customer();
        $password = $this->hasher->hashPassword($userAdmin, $this->customer->getPassword());
        $userAdmin->setFirstName($this->customer->getUserName());
        $userAdmin->setLastName($this->customer->getLastName());
        $userAdmin->setNickName($this->customer->getNickName());
        $userAdmin->setEmail($this->customer->getEmail());
        $userAdmin->setPassword($password);
        $manager->persist($userAdmin);
        $manager->flush();

        $this->addReference(self::SECURITY_ADMIN_USER, $userAdmin);
    }
}
