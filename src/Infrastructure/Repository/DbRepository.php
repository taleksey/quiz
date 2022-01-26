<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;

/** @template T */
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
        $repository = $manager->getRepository($entityName);
        $this->manager = $repository;
        $this->entityManager = $manager->getManager();
    }

    /**
     * @return class-string<T>
     */
    abstract protected function getFullEntityName(): string;
}
