<?php

namespace App\Presentation\Hydrator;

use App\Domain\Quiz\Entity\Answer;
use App\Domain\Quiz\Entity\Question;
use App\Domain\Quiz\Entity\Quiz;
use App\Presentation\DTO\QuizCreateDTO;

class DTOHydrator
{
    /**
     * @param QuizCreateDTO $quizCreateDTO
     * @param int $queue
     * @return Quiz
     */
    public function hydrate(QuizCreateDTO $quizCreateDTO, int $queue): Quiz
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
        //dd($quizName);
        return $quizEntity;
    }
}