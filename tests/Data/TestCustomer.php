<?php

declare(strict_types=1);

namespace App\Tests\Data;

class TestCustomer
{
    private const USER_FIRSTNAME = 'FirstName';
    private const USER_LASTNAME = 'LastName';
    private const USER_NICK_NAME = 'test';
    private const USER_EMAIL = 'test@example.com';
    private const USER_PASSWORD = 'TestPassword';

    public function getUserName(): string
    {
        return self::USER_FIRSTNAME;
    }

    public function getLastName(): string
    {
        return self::USER_LASTNAME;
    }

    public function getEmail(): string
    {
        return self::USER_EMAIL;
    }

    public function getPassword(): string
    {
        return self::USER_PASSWORD;
    }

    public function getNickName(): string
    {
        return self::USER_NICK_NAME;
    }
}
