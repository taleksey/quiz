<?php

namespace App\Infractructure\DataFixtures;

use App\Domain\Quiz\Entity\CustomerType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CustomerTypeFixtures extends Fixture
{
    public const ADMIN_TYPE = 'admin-type';
    public function load(ObjectManager $manager): void
    {
        $customerType = new CustomerType();
        $customerType->setName('Admin');
        $customerType->setAdd(true);
        $customerType->setEdit(true);
        $customerType->setShow(true);
        $manager->persist($customerType);
        $manager->flush();

        $this->addReference(self::ADMIN_TYPE, $customerType);
    }
}
