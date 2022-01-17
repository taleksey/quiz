<?php
declare(strict_types=1);
namespace App\Tests\Unit;

use App\Domain\Quiz\Entity\Quiz;
use App\Domain\Quiz\Service\QuizService;
use App\Infractructure\Repository\QuizRepository;
use PHPUnit\Framework\TestCase;

class QuizServiceTest extends TestCase
{
    public function testGetAllQuizzes(): void
    {
        $quizzesRepository = $this->createMock(QuizRepository::class);
        $quizzesRepository->expects($this->once())
            ->method('getQuizzes')
            ->willReturn($this->getQuizzes());

        $quizService = new QuizService($quizzesRepository);
        $result = $quizService->getAllQuizzes();
        $this->assertSameSize($result, $this->getQuizzes());

        $this->assertEquals($result, $this->getQuizzes());
    }

    /**
     * @return Quiz[]
     */
    public function getQuizzes(): array
    {
        $quiz = new Quiz();
        $quiz->setId(1);
        $quiz->setName('Second');
        $quiz->setQueue(2);

        return [
            $this->getOneQuiz(),
            $quiz
        ];
    }

    /**
     * @return Quiz
     */
    private function getOneQuiz(): Quiz
    {
        $quiz = new Quiz();
        $quiz->setId(1);
        $quiz->setName('First');
        $quiz->setQueue(1);

        return $quiz;
    }

    public function testGetQuiz()
    {
        $quiz = $this->getOneQuiz();
        $quizzesRepository = $this->createMock(QuizRepository::class);
        $quizzesRepository->expects($this->once())
            ->method('getQuizById')
            ->willReturn($this->getOneQuiz());

        $quizService = new QuizService($quizzesRepository);
        $findQuiz = $quizService->getQuizById(1);

        $this->assertEquals($quiz, $findQuiz);
    }
}
