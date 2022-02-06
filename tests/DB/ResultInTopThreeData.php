<?php

namespace App\Tests\DB;

use App\Infrastructure\DataFixtures\QuizFixtures;
use App\Infrastructure\DB\Customer\Customer;
use App\Infrastructure\DB\Quiz\Quiz;
use App\Infrastructure\DB\Statistics\Customer as CustomerDomain;
use App\Infrastructure\DB\Statistics\Quiz as QuizStatistics;
use App\Infrastructure\DB\Statistics\QuizStatistic;
use Doctrine\Persistence\ObjectManager;

class ResultInTopThreeData
{
    /**
     * @var array<int, array<string, string|int>>
     */
    protected array $rawCustomers = [
        [
            'customerEmail' => 'test_one@example.com',
            'customerNick' => 'test_one',
            'customerFirstName' => 'OneFirstName',
            'customerLastName' => 'OneLastName',
            'quizCustomerSeconds' => 2,
            'correctAnswers' => 5
        ],
        [
            'customerEmail' => 'test_two@example.com',
            'customerNick' => 'test_two',
            'customerFirstName' => 'TwoFirstName',
            'customerLastName' => 'TwoLastName',
            'quizCustomerSeconds' => 999999,
            'correctAnswers' => 5
        ],
        [
            'customerEmail' => 'test_three@example.com',
            'customerNick' => 'test_three',
            'customerFirstName' => 'ThreeFirstName',
            'customerLastName' => 'ThreeLastName',
            'quizCustomerSeconds' => 99999,
            'correctAnswers' => 5
        ],
        [
            'customerEmail' => 'test_forth@example.com',
            'customerNick' => 'test_forth',
            'customerFirstName' => 'ForthFirstName',
            'customerLastName' => 'ForthLastName',
            'quizCustomerSeconds' => 9999,
            'correctAnswers' => 5
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        $quiz = $manager->getRepository(Quiz::class)->findOneBy(['name' => QuizFixtures::FIRST_QUIZ]);

        foreach ($this->rawCustomers as $rawCustomers) {
            $customer = new Customer();
            $customer->setEmail($rawCustomers['customerEmail']);
            $customer->setNickName($rawCustomers['customerNick']);
            $customer->setFirstName($rawCustomers['customerFirstName']);
            $customer->setLastName($rawCustomers['customerLastName']);

            $manager->persist($customer);
            $manager->flush();

            $quizDomain = $manager->find(QuizStatistics::class, $quiz->getId());
            $customerDomain = $manager->find(CustomerDomain::class, $customer->getId());

            $statistic = new QuizStatistic();
            $statistic->setCustomer($customerDomain);
            $statistic->setQuiz($quizDomain);
            $statistic->setSpendSecondsQuiz($rawCustomers['quizCustomerSeconds']);
            $statistic->setTotalCorrectAnswers($rawCustomers['correctAnswers']);
            $statistic->setTotalQuestions($quiz->getQuestions()->count());

            $manager->persist($statistic);
            $manager->flush();
        }
    }
}
