<?php

namespace App\Tests\DataFixtures\ORM;

class ResultOnForthPositionWithDifferentCorrectAnswersFixtures extends ResultInTopThreeFixtures
{
    protected array $rawCustomers = [
        [
            'customerEmail' => 'test_one@example.com',
            'customerNick' => 'test_one',
            'customerFirstName' => 'OneFirstName',
            'customerLastName' => 'OneLastName',
            'quizCustomerSeconds' => 1,
            'correctAnswers' => 4
        ],
        [
            'customerEmail' => 'test_two@example.com',
            'customerNick' => 'test_two',
            'customerFirstName' => 'TwoFirstName',
            'customerLastName' => 'TwoLastName',
            'quizCustomerSeconds' => 2,
            'correctAnswers' => 3
        ],
        [
            'customerEmail' => 'test_three@example.com',
            'customerNick' => 'test_three',
            'customerFirstName' => 'ThreeFirstName',
            'customerLastName' => 'ThreeLastName',
            'quizCustomerSeconds' => 3,
            'correctAnswers' => 5
        ],
        [
            'customerEmail' => 'test_forth@example.com',
            'customerNick' => 'test_forth',
            'customerFirstName' => 'ForthFirstName',
            'customerLastName' => 'ForthLastName',
            'quizCustomerSeconds' => 4,
            'correctAnswers' => 5
        ],
        [
            'customerEmail' => 'test_fifth@example.com',
            'customerNick' => 'test_fifth',
            'customerFirstName' => 'FifthFirstName',
            'customerLastName' => 'FifthLastName',
            'quizCustomerSeconds' => 4,
            'correctAnswers' => 5
        ],
        [
            'customerEmail' => 'test_sixth@example.com',
            'customerNick' => 'test_sixth',
            'customerFirstName' => 'SixthFirstName',
            'customerLastName' => 'SixthLastName',
            'quizCustomerSeconds' => 99,
            'correctAnswers' => 5
        ],
    ];
}
