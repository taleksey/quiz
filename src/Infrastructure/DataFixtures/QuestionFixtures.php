<?php

declare(strict_types=1);

namespace App\Infrastructure\DataFixtures;

use App\Infrastructure\DB\Quiz\Question;
use App\Infrastructure\DB\Quiz\Quiz;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class QuestionFixtures extends Fixture implements DependentFixtureInterface
{
    public const FIRST_QUIZ_QUESTION_ONE = 'first-quiz-question-one';

    public const FIRST_QUIZ_QUESTION_SECOND = 'first-quiz-question-second';

    public const FIRST_QUIZ_QUESTION_THIRD = 'first-quiz-question-third';

    public const FIRST_QUIZ_QUESTION_FOURTH = 'first-quiz-question-fourth';

    public const FIRST_QUIZ_QUESTION_FIFTH = 'first-quiz-question-fifth';

    public const SECOND_QUIZ_QUESTION_ONE = 'second-quiz-question-one';

    public const SECOND_QUIZ_QUESTION_SECOND = 'second-quiz-question-second';

    public const SECOND_QUIZ_QUESTION_THIRD = 'second-quiz-question-third';

    public const SECOND_QUIZ_QUESTION_FOURTH = 'second-quiz-question-fourth';

    public const SECOND_QUIZ_QUESTION_FIFTH = 'second-quiz-question-fifth';

    public function load(ObjectManager $manager): void
    {
        $firstQuizQuestion = [
            self::FIRST_QUIZ_QUESTION_ONE => 'How many ocean on Earth?',
            self::FIRST_QUIZ_QUESTION_SECOND => 'What does it mean world ocean?',
            self::FIRST_QUIZ_QUESTION_THIRD => 'Which area take oceans?',
            self::FIRST_QUIZ_QUESTION_FOURTH => 'Which is ocean the biggest?',
            self::FIRST_QUIZ_QUESTION_FIFTH => 'Which is ocean the deeper?',
        ];
        $this->fillOutData($firstQuizQuestion, $manager, QuizFixtures::FIRST_QUIZ);


        $secondQuizQuestion = [
            self::SECOND_QUIZ_QUESTION_ONE => 'What is the black gold?',
            self::SECOND_QUIZ_QUESTION_SECOND => 'What is the biggest city in Eastern?',
            self::SECOND_QUIZ_QUESTION_THIRD => 'What is the longest river in Europe?',
            self::SECOND_QUIZ_QUESTION_FOURTH => 'Which does the river cross Equator two times?',
            self::SECOND_QUIZ_QUESTION_FIFTH => 'What is the biggest island?',
        ];

        $this->fillOutData($secondQuizQuestion, $manager, QuizFixtures::SECOND_QUIZ);
    }

    /**
     * @return array<int, string>
     */
    public function getDependencies(): array
    {
        return [
            QuizFixtures::class
        ];
    }

    /**
     * @param array<string, string> $values
     * @param ObjectManager $manager
     * @param string $type
     * @return void
     */
    private function fillOutData(array $values, ObjectManager $manager, string $type): void
    {
        $queue = 1;
        foreach ($values as $valueKey => $valueItem) {
            /** @var Quiz $reference */
            $reference = $this->getReference($type);
            $firstQuizQuestion = new Question();
            $firstQuizQuestion->setText($valueItem);
            $firstQuizQuestion->setQuiz($reference);
            $firstQuizQuestion->setQueue($queue);
            $manager->persist($firstQuizQuestion);
            $manager->flush();

            $this->addReference($valueKey, $firstQuizQuestion);
            ++$queue;
        }
    }
}
