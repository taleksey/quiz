<?php

declare(strict_types=1);

namespace App\Presentation\Provider;

use App\Domain\Customer\Entity\Customer;
use App\Infrastructure\Repository\DbRepository;
use App\Presentation\Transformers\Auth0\ResponseToCustomerTransformer;
use Doctrine\Persistence\ManagerRegistry;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @extends DbRepository<Customer>
 */
class CustomerEntityProvider extends DbRepository implements UserProviderInterface, OAuthAwareUserProviderInterface
{
    private ResponseToCustomerTransformer $responseToCustomerTransformer;

    public function __construct(ManagerRegistry $manager, ResponseToCustomerTransformer $responseToCustomerTransformer)
    {
        parent::__construct($manager);
        $this->responseToCustomerTransformer = $responseToCustomerTransformer;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $customer = $this->findUser(['email' => $identifier]);
        if (null === $customer) {
            $exception = new UserNotFoundException(sprintf("User '%s' not found.", $identifier));
            $exception->setUserIdentifier($identifier);

            throw $exception;
        }

        return $customer;
    }

    public function loadUserByOAuthUserResponse(UserResponseInterface $response): UserInterface
    {
        $customer = $this->manager->findOneBy(['email' => $response->getEmail()]);
        if ($customer) {
            return $customer;
        }

        $customer = $this->responseToCustomerTransformer->transform($response);

        $this->entityManager->persist($customer);
        $this->entityManager->flush();

        return $this->loadUserByIdentifier($response->getEmail());
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$this->supportsClass(\get_class($user))) {
            throw new UnsupportedUserException(sprintf('Unsupported user class "%s"', \get_class($user)));
        }

        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }


    public function supportsClass(string $class): bool
    {
        return 'App\\Domain\\Quiz\\Entity\\Customer' === $class;
    }

    protected function getFullEntityName(): string
    {
        return 'App\Infrastructure\DB\Customer\Customer';
    }

    /**
     * @param array<string, string> $criteria
     * @return UserInterface|null
     */
    private function findUser(array $criteria): ?UserInterface
    {
        return $this->manager->findOneBy($criteria);
    }
}
