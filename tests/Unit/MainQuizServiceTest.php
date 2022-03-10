<?php
declare(strict_types=1);
namespace App\Tests\Unit;

use App\Domain\Quiz\Service\QuizService;
use App\Infrastructure\DB\Customer\Customer;
use App\Infrastructure\DB\Quiz\Answer;
use App\Infrastructure\DB\Quiz\Question;
use App\Infrastructure\DB\Quiz\Quiz;
use App\Infrastructure\Repository\QuizRepository;
use App\Presentation\DTO\Quiz\CreateDTO;
use App\Presentation\DTO\Quiz\QuestionAnswerCreateDTO;
use App\Presentation\DTO\Quiz\QuestionCreateDTO;
use App\Presentation\Hydrator\DTOHydrator;
use App\Presentation\Service\MainQuizService;
use PHPUnit\Framework\TestCase;

class MainQuizServiceTest extends TestCase
{
    /** @var array<string, array<string, array<int, array<string, array<int, array<string, bool|string>>|string>>>>  */
    private array $dataForCreatingQuizzes = [
        'Quiz Test' => [
            'questions' => [
                [
                    'questionName' => 'Quiz Question One',
                    'answers' => [
                        [
                            'subject' => 'Quiz Question One Answer One', 'isCorrect' => false
                        ],
                        [
                            'subject' => 'Quiz Question One Answer Two', 'isCorrect' => true
                        ],
                        [
                            'subject' => 'Quiz Question One Answer Three', 'isCorrect' => false
                        ]
                    ]
                ],
                [
                    'questionName' => 'Quiz Question Two',
                    'answers' => [
                        [
                            'subject' => 'Quiz Question Two Answer One', 'isCorrect' => true
                        ],
                        [
                            'subject' => 'Quiz Question Two Answer Two', 'isCorrect' => false
                        ],
                        [
                            'subject' => 'Quiz Question Two Answer Three', 'isCorrect' => false
                        ]
                    ]
                ]
            ]
        ]
    ];

    private const TOTAL_QUIZZES = 2;

    private const CUSTOMER_EMAIL = 'test@example.com';

    /**
     * @dataProvider getQuizzesAndLinkedQuizDTO
     *
     * @param Quiz $quiz
     * @param CreateDTO $quizDTO
     * @return void
     */
    public function testCreateNewQuiz(Quiz $quiz, CreateDTO $quizDTO): void
    {
        $quizzesRepository = $this->createMock(QuizRepository::class);
        $quizzesRepository->expects($this->any())
            ->method('getTotalQuizzes')
            ->willReturn(self::TOTAL_QUIZZES)
           ;
        $quizzesRepository->method('save')
            ->with($quiz);

        $customer = $this->createMock(Customer::class);
        $customer->expects($this->any())
        ->method('getEmail')
        ->willReturn(self::CUSTOMER_EMAIL);

        $quizService = new QuizService($quizzesRepository);
        $DTOHydrator = new DTOHydrator();
        $mainQuiz = new MainQuizService($DTOHydrator, $quizService);

        $mainQuiz->createQuiz($quizDTO, $customer);
        $this->assertNotEquals($quizDTO, $quiz);
    }

    /**
     * @return array <int, array<string, Quiz|CreateDTO>>
     */
    public function getQuizzesAndLinkedQuizDTO(): array
    {
        $currentQuizOrderNumber = 1;
        $linkQuizWithQuizDTO = [];
        foreach ($this->dataForCreatingQuizzes as $quizName => $dataForCreatingQuiz) {
            $quiz = new Quiz();
            $quiz->setName($quizName);
            $quiz->setActive(true);
            $quiz->setQueue(self::TOTAL_QUIZZES + $currentQuizOrderNumber);
            $quiz->setEmail(self::CUSTOMER_EMAIL);
            $questions = $dataForCreatingQuiz['questions'] ?? [];
            foreach ($questions as $questionIndex => $questionData) {
                $question = new Question();
                $question->setText($questionData['questionName']);
                $question->setQueue($questionIndex + 1);
                foreach ($questionData['answers'] as $answerIndex => $answerData) {
                    $answer = new Answer();
                    $answer->setText($answerData['subject']);
                    $answer->setQueue($answerIndex + 1);
                    $answer->setCorrect($answerData['isCorrect']);
                    $answer->setQuestion($question);
                    $question->setAnswer($answer);
                }
                $quiz->setQuestions($question);
                $question->setQuiz($quiz);
            }
            $currentQuizOrderNumber++;

            $quizDTO = $this->createQuizDTO($quiz);
            $linkQuizWithQuizDTO[] = [
               'quiz' => $quiz,
               'dto' => $quizDTO
            ];
        }

        return $linkQuizWithQuizDTO;
    }

    private function createQuizDTO(Quiz $quiz): CreateDTO
    {
        $quizCreateDTO = new CreateDTO();
        $quizCreateDTO->setName($quiz->getName());
        $questions = [];
        foreach ($quiz->getQuestions() as $question) {
            $questionDTO = new QuestionCreateDTO();
            $questionDTO->setText($question->getText());
            $answers = [];
            foreach ($question->getAnswers() as $answer) {
                $answerDTO = new QuestionAnswerCreateDTO();
                $answerDTO->setText($answer->getText());
                $answerDTO->setCorrect($answer->isCorrect());
                $answers[] = $answerDTO;
            }
            $questionDTO->setAnswers($answers);
            $questions[] = $questionDTO;
        }
        $quizCreateDTO->setQuestions($questions);

        return $quizCreateDTO;
    }
}
