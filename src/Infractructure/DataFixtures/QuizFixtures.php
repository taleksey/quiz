<?php

namespace App\Infractructure\DataFixtures;

use App\Domain\Quiz\Entity\Customer;
use App\Domain\Quiz\Entity\Quiz;
use App\Infractructure\ValueObject\TestCustomer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class QuizFixtures extends Fixture implements DependentFixtureInterface
{
    public const FIRST_QUIZ = 'Ocean';
    public const SECOND_QUIZ = 'Geography';
    private TestCustomer $customer;

    public function __construct(TestCustomer $customer)
    {
        $this->customer = $customer;
    }

    public function load(ObjectManager $manager): void
    {
        $quizArray = [
            self::FIRST_QUIZ,
            self::SECOND_QUIZ
        ];
        foreach ($quizArray as $queue => $quiz) {
            $quizFirst = new Quiz();
            $quizFirst->setName($quiz);
            $quizFirst->setActive(true);
            $quizFirst->setEmail($this->customer->getEmail());
            $quizFirst->setQueue($queue + 1);
            $manager->persist($quizFirst);
            $manager->flush();

            $this->addReference($quiz, $quizFirst);
        }
    }

    /**
     * @return array<int, string>
     */
    public function getDependencies(): array
    {
        return [
            SecurityCustomerFixtures::class
        ];
    }
}
