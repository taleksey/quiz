<?php

namespace App\Infractructure\DataFixtures;

use App\Domain\Quiz\Entity\Customer;
use App\Domain\Quiz\Entity\CustomerType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CustomerFixtures extends Fixture implements DependentFixtureInterface
{
    public const ADMIN_USER = 'admin';

    public function load(ObjectManager $manager): void
    {
        /** @var CustomerType $reference */
        $reference = $this->getReference(CustomerTypeFixtures::ADMIN_TYPE);
        $userAdmin = new Customer();
        $userAdmin->setFirstName('Admin');
        $userAdmin->setLastName('Admin');
        $userAdmin->setCustomerType($reference);
        $manager->persist($userAdmin);
        $manager->flush();

        $this->addReference(self::ADMIN_USER, $userAdmin);
    }

    /**
     * @return array<int, string>
     */
    public function getDependencies(): array
    {
        return [
            CustomerTypeFixtures::class
        ];
    }
}
