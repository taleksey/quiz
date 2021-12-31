<?php

namespace App\Tests\Unit;

use App\Domain\Quiz\Entity\Answer;
use App\Domain\Quiz\Entity\Question;
use App\Domain\Quiz\Entity\Quiz;
use App\Domain\Quiz\Repository\QuizzesRepository;
use App\Domain\Quiz\Service\QuizService;
use App\Presentation\DTO\QuizCreateDTO;
use App\Presentation\DTO\QuizQuestionAnswerCreateDTO;
use App\Presentation\DTO\QuizQuestionCreateDTO;
use App\Presentation\Hydrator\DTOHydrator;
use App\Presentation\Service\MainQuizService;
use PHPUnit\Framework\TestCase;

class MainQuizServiceTest extends TestCase
{
    private const TOTAL_QUIZZES = 2;
    private const QUESTION_NAME = [
        1 => 'Quiz Question One', 2=>'Quiz Question Two'
    ];
    private const QUIZ_NAME = 'Quiz Test';
    private const QUESTION_ANSWER_NAME = [
        1 => [
            1 => 'Quiz Question One Answer One',
            2 => 'Quiz Question One Answer Two',
            3 => 'Quiz Question One Answer Three'
            ],
        2 => [
            1 => 'Quiz Question Two Answer One',
            2 => 'Quiz Question Two Answer Two',
            3 => 'Quiz Question Two Answer Three'
        ]
    ];

    public function testCreateNewQuiz()
    {
        $quizzesRepository = $this->createMock(QuizzesRepository::class);
        $quizzesRepository->expects($this->any())
            ->method('getTotalQuizzes')
            ->willReturn(self::TOTAL_QUIZZES)
           ;
        $quizzesRepository->method('save')
        ->with($this->getEntity());
        $quizService = new QuizService($quizzesRepository);
        $DTOHydrator = new DTOHydrator();
        $mainQuiz = new MainQuizService($DTOHydrator, $quizService);
        $mainQuiz->createQuiz($this->getDTO());
        $this->assertNotEquals($this->getDTO(), $this->getEntity());
    }

    /**
     * @return Quiz
     */
    private function getEntity(): Quiz
    {
        $quiz = new Quiz();
        $quiz->setName(self::QUIZ_NAME);
        $quiz->setActive(true);
        $quiz->setQueue(self::TOTAL_QUIZZES + 1);

        for ($i=1; $i < 3; $i++) {
            $question = new Question();
            $question->setText(self::QUESTION_NAME[$i]);
            $question->setQueue($i);
            for ($k=1; $k < 4; $k++) {
                $answer = new Answer();
                $answer->setText(self::QUESTION_ANSWER_NAME[$i][$k]);
                $answer->setQueue($k);
                $isCorrect = 2 === $k;
                $answer->setCorrect($isCorrect);
                $answer->setQuestion($question);
                $question->setAnswer($answer);
            }
            $quiz->setQuestions($question);
            $question->setQuiz($quiz);
        }

        return $quiz;
    }

    private function getDTO(): QuizCreateDTO
    {
        $quizCreateDTO = new QuizCreateDTO();
        $quizCreateDTO->setName(self::QUIZ_NAME);
        $questions = [];
        for ($i = 1; $i < 3; $i++) {
            $question = new QuizQuestionCreateDTO();
            $question->setText(self::QUESTION_NAME[$i]);
            $answers = [];
            for ($k=1; $k < 4; $k++) {
                $answer = new QuizQuestionAnswerCreateDTO();
                $answer->setText(self::QUESTION_ANSWER_NAME[$i][$k]);
                $answer->setCorrect(2 === $k);
                $answers[] = $answer;
            }
            $question->setAnswers($answers);
            $questions[] = $question;
        }
        $quizCreateDTO->setQuestions($questions);

        return $quizCreateDTO;
    }
}
