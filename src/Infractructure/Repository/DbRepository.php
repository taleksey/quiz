<?php

declare(strict_types=1);

namespace App\Infractructure\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;

abstract class DbRepository
{
    /** @var mixed */
    protected mixed $manager;

    /** @var ObjectManager  */
    protected ObjectManager $entityManager;

    /**
     * @param ManagerRegistry $manager
     */
    public function __construct(ManagerRegistry $manager)
    {
        $entityName = $this->getFullEntityName();
        $this->manager = $manager->getRepository($entityName);
        $this->entityManager = $manager->getManager();
    }

    abstract protected function getFullEntityName(): string;
}
