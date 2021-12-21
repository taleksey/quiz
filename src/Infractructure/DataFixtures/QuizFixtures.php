<?php

namespace App\Infractructure\DataFixtures;

use App\Domain\Quiz\Entity\Quiz;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class QuizFixtures extends Fixture implements DependentFixtureInterface
{
    public const FIRST_QUIZ = 'Ocean';
    public const SECOND_QUIZ = 'Geography';

    public function load(ObjectManager $manager)
    {
        $quizArray = [
            self::FIRST_QUIZ,
            self::SECOND_QUIZ
        ];
        foreach ($quizArray as $queue => $quiz) {
            $quizFirst = new Quiz();
            $quizFirst->setName($quiz);
            $quizFirst->setActive(true);
            $quizFirst->setCustomer($this->getReference(CustomerFixtures::ADMIN_USER));
            $quizFirst->setQueue($queue + 1);
            $manager->persist($quizFirst);
            $manager->flush();

            $this->addReference($quiz, $quizFirst);
        }
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            CustomerFixtures::class
        ];
    }
}
