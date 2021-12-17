<?php

namespace App\Infractructure\DataFixtures;

use App\Domain\Quiz\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CustomerFixtures extends Fixture implements DependentFixtureInterface
{
    public const ADMIN_USER = 'admin';

    public function load(ObjectManager $manager)
    {
        $userAdmin = new Customer();
        $userAdmin->setFirstName('Admin');
        $userAdmin->setLastName('Admin');
        $userAdmin->setCustomerType($this->getReference(CustomerTypeFixtures::ADMIN_TYPE));
        $manager->persist($userAdmin);
        $manager->flush();

        $this->addReference(self::ADMIN_USER, $userAdmin);
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            CustomerTypeFixtures::class
        ];
    }
}
