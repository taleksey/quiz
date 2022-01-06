<?php

namespace App\Infractructure\Repository;

use Doctrine\Persistence\ManagerRegistry;

abstract class DbRepository
{
    /** @var mixed  */
    protected mixed $manager;

    /**
     * @param ManagerRegistry $manager
     */
    public function __construct(ManagerRegistry $manager)
    {
        $entityName = $this->getFullEntityName();
        $this->manager = $manager->getRepository($entityName);
    }

    abstract protected function getFullEntityName(): string;
}
