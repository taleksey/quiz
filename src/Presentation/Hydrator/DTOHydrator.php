<?php

declare(strict_types=1);

namespace App\Presentation\Hydrator;

use App\Infrastructure\DB\Quiz\Answer;
use App\Infrastructure\DB\Quiz\Question;
use App\Infrastructure\DB\Quiz\Quiz;
use App\Presentation\DTO\Quiz\CreateDTO;

class DTOHydrator
{
    /**
     * @param CreateDTO $quizCreateDTO
     * @param int $queue
     * @return Quiz
     */
    public function hydrate(CreateDTO $quizCreateDTO, int $queue): Quiz
    {
        $quizEntity = new Quiz();
        $quizEntity->setName($quizCreateDTO->getName());
        $quizEntity->setQueue($queue);
        $quizEntity->setActive(true);
        foreach ($quizCreateDTO->getQuestions() as $key => $question) {
            $queueQuestion = $key + 1;
            $questionEntity = new Question();
            $questionEntity->setQueue($queueQuestion);
            $questionEntity->setText($question->getText());
            $questionEntity->setQuiz($quizEntity);
            foreach ($question->getAnswers() as $keyAnswer => $answer) {
                $queueAnswer = $keyAnswer + 1;
                $answerEntity = new Answer();
                $answerEntity->setText($answer->getText());
                $answerEntity->setQueue($queueAnswer);
                $answerEntity->setCorrect($answer->isCorrect());
                $questionEntity->setAnswer($answerEntity);
                $answerEntity->setQuestion($questionEntity);
            }
            $quizEntity->setQuestions($questionEntity);
        }

        return $quizEntity;
    }
}
