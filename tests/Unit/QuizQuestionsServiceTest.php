<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Domain\Quiz\Repository\Interfaces\QuestionAnswersRepository;
use App\Domain\Quiz\Repository\Interfaces\QuestionsRepository;
use App\Domain\Quiz\Repository\Interfaces\ResultRepository;
use App\Domain\Quiz\Service\QuizQuestionsService;
use App\Presentation\DTO\Quiz\QuestionAnswerRequestDTO;
use PHPUnit\Framework\TestCase;

class QuizQuestionsServiceTest extends TestCase
{
    public function testCorrectCheckingFirstStepWhenInitializeQuiz()
    {
        $quizzesRepository = $this->createMock(QuestionsRepository::class);
        $questionAnswersRepository = $this->createMock(QuestionAnswersRepository::class);
        $resultRepository = $this->createMock(ResultRepository::class);
        $resultRepository->expects($this->any())
            ->method('getSavedCustomerAnswers')
            ->willReturn([])
        ;

        $quizQuestionsService = new QuizQuestionsService($quizzesRepository, $questionAnswersRepository, $resultRepository);

        $quizQuestionAnswerDTO = new QuestionAnswerRequestDTO([
            'quizId' => 3,
            'step' => 1,
            'answerId' => 4
        ]);

        $result = $quizQuestionsService->doesCustomerSelectCorrectStep($quizQuestionAnswerDTO);

        $this->assertTrue($result);
    }

    public function testCheckWrongStepWhenClientSelectStepButHeDidNotFinishStepsBefore()
    {
        $quizzesRepository = $this->createMock(QuestionsRepository::class);
        $questionAnswersRepository = $this->createMock(QuestionAnswersRepository::class);
        $resultRepository = $this->createMock(ResultRepository::class);
        $resultRepository->expects($this->any())
            ->method('getSavedCustomerAnswers')
            ->willReturn([
                1 => 1,
                2 => 0,
            ])
        ;

        $quizQuestionsService = new QuizQuestionsService($quizzesRepository, $questionAnswersRepository, $resultRepository);

        $quizQuestionAnswerDTO = $this->initialQuestionAnswerRequestDTO();

        $result = $quizQuestionsService->doesCustomerSelectCorrectStep($quizQuestionAnswerDTO);

        $this->assertFalse($result);
    }

    public function testCheckCorrectStepWhenClientPassedAllStepsBeforeCurrent()
    {
        $quizzesRepository = $this->createMock(QuestionsRepository::class);
        $questionAnswersRepository = $this->createMock(QuestionAnswersRepository::class);
        $resultRepository = $this->createMock(ResultRepository::class);
        $resultRepository->expects($this->any())
            ->method('getSavedCustomerAnswers')
            ->willReturn([
                1 => 1,
                2 => 0,
                3 => 0,
            ])
        ;

        $quizQuestionsService = new QuizQuestionsService($quizzesRepository, $questionAnswersRepository, $resultRepository);

        $quizQuestionAnswerDTO = $this->initialQuestionAnswerRequestDTO();

        $result = $quizQuestionsService->doesCustomerSelectCorrectStep($quizQuestionAnswerDTO);

        $this->assertTrue($result);
    }

    public function testNextStepWhenInitializeQuiz()
    {
        $quizzesRepository = $this->createMock(QuestionsRepository::class);
        $questionAnswersRepository = $this->createMock(QuestionAnswersRepository::class);
        $resultRepository = $this->createMock(ResultRepository::class);
        $resultRepository->expects($this->any())
            ->method('getSavedCustomerAnswers')
            ->willReturn([])
        ;

        $quizQuestionsService = new QuizQuestionsService($quizzesRepository, $questionAnswersRepository, $resultRepository);

        $quizQuestionAnswerDTO = new QuestionAnswerRequestDTO([
        ]);

        $result = $quizQuestionsService->getNextCorrectStep($quizQuestionAnswerDTO);

        $this->assertEquals(1, $result);
    }

    public function testNextStepWhenClientPassedSomeSteps()
    {
        $quizzesRepository = $this->createMock(QuestionsRepository::class);
        $questionAnswersRepository = $this->createMock(QuestionAnswersRepository::class);
        $resultRepository = $this->createMock(ResultRepository::class);
        $resultRepository->expects($this->any())
            ->method('getSavedCustomerAnswers')
            ->willReturn([
                1 => 1,
                2 => 0,
            ])
        ;

        $quizQuestionsService = new QuizQuestionsService($quizzesRepository, $questionAnswersRepository, $resultRepository);

        $quizQuestionAnswerDTO = $this->initialQuestionAnswerRequestDTO();

        $result = $quizQuestionsService->getNextCorrectStep($quizQuestionAnswerDTO);

        $this->assertEquals(3, $result);
    }

    private function initialQuestionAnswerRequestDTO(): QuestionAnswerRequestDTO
    {
        return new QuestionAnswerRequestDTO([
            'quizId' => 3,
            'step' => 4,
            'answerId' => 4
        ]);
    }
}
