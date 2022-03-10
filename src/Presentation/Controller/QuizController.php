<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Domain\Customer\Entity\Customer;
use App\Domain\Quiz\Service\QuizQuestionAnswersService;
use App\Domain\Quiz\Service\QuizQuestionsService;
use App\Domain\Quiz\Service\QuizService;
use App\Domain\Statistics\Service\QuizStatisticService;
use App\Infrastructure\Service\AuthService;
use App\Presentation\DTO\Quiz\QuestionAnswerRequestDTO;
use App\Presentation\Form\Quiz\QuizType;
use App\Presentation\Service\MainQuizService;
use App\Presentation\Transformers\Statistic\StatisticTransformer;
use App\Presentation\Transformers\Statistic\StatisticViewTransformer;
use App\Presentation\Transformers\TransformJsonToRequest;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends AbstractController
{
    #[Route('/', name: 'homepage', methods: ['GET'])]
    public function index(QuizService $quizService): Response
    {
        $quizzes = $quizService->getAllQuizzes();

        return $this->render('quizzes/index.html.twig', ['quizzes' => $quizzes]);
    }

    #[Route('/quiz/{id}', name: 'quiz_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function showQuiz(QuizService $quizService, QuizQuestionAnswersService $quizQuestionAnswersService, int $id): Response
    {
        $quiz = $quizService->getQuizById($id);
        if (! $quiz) {
            throw $this->createNotFoundException(
                'No quiz found for id ' . $id
            );
        }
        $totalQuestions = $quiz->getQuestions()->count();
        $passedQuestion = $quizQuestionAnswersService->getLastPassedQuestion($id);

        if ($passedQuestion && $totalQuestions > $passedQuestion) {
            $nextCorrectStep = $passedQuestion + 1;
            return $this->redirectToRoute('quiz_questions', ['id' => $id, 'step' => $nextCorrectStep]);
        }

        return $this->render('quiz/index.html.twig', ['quiz' => $quiz]);
    }

    #[Route('/quiz/{id}/question/{step}', name: 'quiz_questions', methods: ['GET'])]
    public function quizQuestion(
        QuizService $quizService,
        QuizQuestionsService $quizQuestionsService,
        QuestionAnswerRequestDTO $quizQuestionAnswerDTO
    ): Response {
        $question = $quizQuestionsService->getQuestionByQuizIdAndQueue($quizQuestionAnswerDTO);
        $quiz = $quizService->getQuizById($quizQuestionAnswerDTO->getQuizId());
        if (! $question) {
            throw $this->createNotFoundException(
                'No question found for quiz ' . $quizQuestionAnswerDTO->getQuizId()
            );
        }

        $customerSelectCorrectStep = $quizQuestionsService->doesCustomerSelectCorrectStep($quizQuestionAnswerDTO);
        if (! $customerSelectCorrectStep) {
            $nextCorrectStep = $quizQuestionsService->getNextCorrectStep($quizQuestionAnswerDTO);

            return $this->redirectToRoute('quiz_questions', ['id' => $quizQuestionAnswerDTO->getQuizId(), 'step' => $nextCorrectStep]);
        }

        $totalQuestions = $quizQuestionsService->getTotalQuestions($quizQuestionAnswerDTO->getQuizId());

        return $this->render(
            'question/index.html.twig',
            [
                'step' => $quizQuestionAnswerDTO->getQuestionStep()->getStepId(),
                'totalQuestions' => $totalQuestions,
                'question' => $question,
                'answers' => $question->getAnswers(),
                'isFirstQuestion' => $quizQuestionAnswerDTO->getQuestionStep()->isFirstStep(),
                'isLastQuestion' => $quizQuestionAnswerDTO->getQuestionStep()->isFinalStep($totalQuestions),
                'quiz' => $quiz
            ]
        );
    }

    /**
     * @param QuizQuestionsService $quizQuestionsService
     * @param QuizQuestionAnswersService $quizQuestionAnswersService
     * @param QuestionAnswerRequestDTO $quizQuestionAnswerDTO
     * @param int $id
     * @param int $step
     * @return RedirectResponse|Response
     * @throws NonUniqueResultException
     */
    #[Route('/quiz/{id}/question/{step}', name: 'question_answers')]
    public function saveAnswerQuestion(
        QuizQuestionsService $quizQuestionsService,
        QuizQuestionAnswersService $quizQuestionAnswersService,
        QuestionAnswerRequestDTO $quizQuestionAnswerDTO,
        int $id,
        int $step
    ): RedirectResponse|Response {
        if (! $quizQuestionAnswerDTO->isCustomerSelectedAnswer()) {
            return $this->redirectToRoute('quiz_questions', ['id' => $id, 'step' => $step]);
        }

        $answer = $quizQuestionsService->getAnswer($quizQuestionAnswerDTO);
        $quizQuestionAnswersService->save($quizQuestionAnswerDTO, $answer->isCorrect());
        $totalQuestions = $quizQuestionsService->getTotalQuestions($quizQuestionAnswerDTO->getQuizId());

        return $this->render(
            'question/answer/index.html.twig',
            [
                'answer' => $answer,
                'question' => $answer->getQuestion(),
                'step' => $step,
                'nextStep' => $quizQuestionAnswerDTO->getQuestionStep()->nextStepId(),
                'isCorrectAnswer' => $answer->isCorrect(),
                'quizId' => $id,
                'isFinalStep' => $quizQuestionAnswerDTO->getQuestionStep()->isFinalStep($totalQuestions)
            ]
        );
    }

    #[Route('/quiz/{id}/result', name: 'quiz_result', methods: ["GET"])]
    public function quizResult(
        QuizQuestionAnswersService $quizQuestionAnswersService,
        QuizQuestionsService       $quizQuestionsService,
        QuizService                $quizService,
        QuizStatisticService       $quizStatisticService,
        StatisticTransformer       $customerTransformer,
        StatisticViewTransformer   $statisticViewTransformer,
        int                        $id
    ): Response {
        $quiz = $quizService->getQuizById($id);
        if (! $quiz) {
            throw $this->createNotFoundException(
                'No quiz found for id ' . $id
            );
        }
        /** @var Customer $customer */
        $customer = $this->getUser();

        $statisticDTO = $customerTransformer->transform($id, $customer->getId());
        $quizResult = $quizQuestionAnswersService->getQuizResult($id);
        if (! $quizResult->isAnswers()) {
            $totalCorrectAnswers = $quizStatisticService->getTotalCorrectAnswersByCustomer($statisticDTO->getCustomer(), $id);
        } else {
            $quizStatisticService->saveResultQuiz($statisticDTO);
            $totalCorrectAnswers = $quizQuestionAnswersService->getTotalCorrectAnswers($id);
            $quizQuestionAnswersService->cleanQuizAnswers($id);
        }
        $top3Statistics = $quizStatisticService->getQuizStatistics($id);
        $statisticPosition = $quizStatisticService->getPositionCurrentCustomer($statisticDTO->getCustomer(), $id);

        return $this->render(
            'question/answer/result.html.twig',
            [
                'totalCorrectAnswers' => $totalCorrectAnswers,
                'totalQuestions' => $quizQuestionsService->getTotalQuestions($id),
                'quiz' => $quizService->getQuizById($id),
                'top3Statistics' => $statisticViewTransformer->transform($top3Statistics),
                'statisticPosition' => $statisticPosition,
                'currentCustomer' => $statisticViewTransformer->transformCustomer($customer)
            ]
        );
    }

    #[Route('/quiz/new', name: 'quiz_new')]
    public function createQuiz(Request $request, MainQuizService $mainQuizService, AuthService $authService): RedirectResponse|Response
    {
        if (! $authService->isCustomerAuthorizedForCreatingQuiz()) {
            return $this->render('quiz/forbidden/index.html.twig');
        }

        $secretToken = $this->getParameter('app.secretToken');
        return $this->render('quiz/create/index.html.twig', ['token' => $secretToken]);
    }

    #[Route('/quiz/create', name: 'quiz_create', methods: ["POST"])]
    public function createNewQuiz(Request $request, AuthService $authService, TransformJsonToRequest $jsonToRequest, MainQuizService $mainQuizService): Response
    {
        if (! $authService->isCustomerAuthorizedForCreatingQuiz()) {
            return new Response(json_encode(['message' => 'You have to authorize']), 403);
        }
        $secretToken = $this->getParameter('app.secretToken');

        $request = $jsonToRequest->transform($request);
        $token = $request->request->get('token');
        $csrfToken = $request->request->get('csrfToken');
        $request->request->remove('csrfToken');
        $form = $this->createForm(QuizType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($secretToken !== $token || ! $this->isCsrfTokenValid('item', $csrfToken)) {
                return new Response(json_encode(['message' => 'You don\'t have access to this page']), 403);
            }

            if (! $form->isValid()) {
                return new Response(json_encode(['message' => (string) $form->getErrors(true, false)]), 403);
            }

            $task = $form->getData();
            $mainQuizService->createQuiz($task);
            return new Response(json_encode(['message' => 'OK']), 200);
        }

        return new Response(json_encode(['message' => 'Something wrong']), 404);
    }

    #[Route('/quiz/created', name: 'quiz_success')]
    public function createdQuiz(): Response
    {
        return $this->renderForm('quiz/created/index.html.twig');
    }
}
