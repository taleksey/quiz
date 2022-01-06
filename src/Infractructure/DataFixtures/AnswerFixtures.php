<?php

namespace App\Infractructure\DataFixtures;

use App\Domain\Quiz\Entity\Answer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AnswerFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $firstQuestion = [
            QuestionFixtures::FIRST_QUIZ_QUESTION_ONE => [
                '4' => false,
                '5' => true,
                '6' => false
            ],
            QuestionFixtures::FIRST_QUIZ_QUESTION_SECOND => [
                'All oceans are taken together' => true,
                'Atlantic and Indian' => false,
                'Arctic and Pacific' => false
            ],
            QuestionFixtures::FIRST_QUIZ_QUESTION_THIRD => [
                '60%' => false,
                '70%' => true,
                '80%' => false
            ],
            QuestionFixtures::FIRST_QUIZ_QUESTION_FOURTH => [
                'Pacific' => true,
                'Atlantic' => false,
                'Indian' => false
            ],
            QuestionFixtures::FIRST_QUIZ_QUESTION_FIFTH => [
                'Pacific' => true,
                'Indian' => false,
                'Atlantic' => false
            ]
        ];
        $this->fillOutAnswers($firstQuestion, $manager);


        $secondQuestion = [
            QuestionFixtures::SECOND_QUIZ_QUESTION_ONE => [
                'coal' => true,
                'ground' => false,
                'oil' => false
            ],
            QuestionFixtures::SECOND_QUIZ_QUESTION_SECOND => [
                'Colombo' => false,
                'Tokio' => true,
                'Cairo' => false
            ],
            QuestionFixtures::SECOND_QUIZ_QUESTION_THIRD => [
                'Dnieper' => false,
                'Seine' => false,
                'Volga' => true
            ],
            QuestionFixtures::SECOND_QUIZ_QUESTION_FOURTH => [
                'Amazon' => false,
                'Congo' => true,
                'Mississippi' => false,
                'Don' => false
            ],
            QuestionFixtures::SECOND_QUIZ_QUESTION_FIFTH => [
                'Greenland' => true,
                'Bali' => false,
                'Madagascar' => false,
                'Mauritius' => false
            ]
        ];

        $this->fillOutAnswers($secondQuestion, $manager);
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            QuestionFixtures::class
        ];
    }

    /**
     * @param array $values
     * @param ObjectManager $manager
     * @return void
     */
    private function fillOutAnswers(array $values, ObjectManager $manager)
    {
        foreach ($values as $key => $itemsArray) {
            $queue = 1;
            foreach ($itemsArray as $keyItem => $isCorrectAnswer) {
                $answer = new Answer();
                $answer->setText($keyItem);
                $answer->setCorrect($isCorrectAnswer);
                $answer->setQuestion($this->getReference($key));
                $answer->setQueue($queue);
                $manager->persist($answer);
                $manager->flush();
                ++$queue;
            }
        }
    }
}
