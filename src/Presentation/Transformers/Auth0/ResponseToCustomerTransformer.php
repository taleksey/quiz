<?php

declare(strict_types=1);

namespace App\Presentation\Transformers\Auth0;

use App\Infrastructure\DB\Customer\Customer;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;

class ResponseToCustomerTransformer
{
    public function transform(UserResponseInterface $userResponse): Customer
    {
        $userRawData = $userResponse->getData();
        $firstName = $userResponse->getFirstName() ?? $userRawData['given_name'] ?? '';
        $lastName = $userResponse->getLastName() ?? $userRawData['family_name'] ?? '';
        $isEmailVerified = $userRawData['email_verified'] ?? false;

        $customer = new Customer();
        $customer->setEmail($userResponse->getEmail());
        $customer->setNickName($userResponse->getNickname());
        $customer->setFirstName($firstName);
        $customer->setLastName($lastName);
        $customer->setIsVerified($isEmailVerified);
        $customer->setPassword('');
        return $customer;
    }
}
