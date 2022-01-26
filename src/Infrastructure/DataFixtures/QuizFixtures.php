<?php

declare(strict_types=1);

namespace App\Infrastructure\DataFixtures;

use App\Infrastructure\Entity\Quiz\Quiz;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class QuizFixtures extends Fixture
{
    public const FIRST_QUIZ = 'Ocean';
    public const SECOND_QUIZ = 'Geography';
    public const USER_EMAIL = 'test@example.com';

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
            $quizFirst->setEmail(self::USER_EMAIL);
            $quizFirst->setQueue($queue + 1);
            $manager->persist($quizFirst);
            $manager->flush();

            $this->addReference($quiz, $quizFirst);
        }
    }
}
