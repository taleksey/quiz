<?php

namespace App\Infractructure\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use \Doctrine\Persistence\ObjectRepository;

abstract class DbRepository
{
    /** @var ObjectRepository|EntityManager  */
    protected ObjectRepository|EntityManager $manager;

    /**
     * @param ManagerRegistry $manager
     */
    public function __construct(ManagerRegistry $manager)
    {
        $entityName = $this->getFullEntityName();
        $entity = new $entityName;
        $this->manager = $manager->getRepository($entity::class);
    }

    abstract protected function getFullEntityName(): string;
}
