<?php

declare(strict_types=1);

namespace App\Infractructure\Repository;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

abstract class SessionRepository
{
    protected SessionInterface $manager;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->manager = $requestStack->getSession();
    }
}
