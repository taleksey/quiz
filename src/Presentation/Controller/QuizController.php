<?php

namespace App\Presentation\Controller;

use App\Domain\Quiz\Service\QuizQuestionAnswersService;
use App\Domain\Quiz\Service\QuizQuestionsService;
use App\Domain\Quiz\Service\QuizService;
use App\Presentation\Service\MainQuizService;
use App\Presentation\Transformers\RequestToQuizQuestionAnswerDTOTransformer;
use Doctrine\ORM\NonUniqueResultException;
use App\Presentation\Form\QuizType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

class QuizController extends AbstractController
{
    /**
     * @param QuizService $quizService
     * @return Response
     */
    #[Route('/', name: 'homepage', methods: ['GET'])]
    public function index(QuizService $quizService): Response
    {
        $quizzes = $quizService->getAllQuizzes();

        return $this->render('quizzes/index.html.twig', ['quizzes' => $quizzes]);
    }

    /**
     * @param QuizService $quizService
     * @param int $id
     * @return Response
     */
    #[Route('/quiz/{id}', name: 'quiz_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function showQuiz(QuizService $quizService, int $id): Response
    {
        $quiz = $quizService->getQuizById($id);
        if (! $quiz) {
            throw $this->createNotFoundException(
                'No quiz found for id ' . $id
            );
        }
        return $this->render('quiz/index.html.twig', ['quiz' => $quiz]);
    }

    /**
     * @param QuizService $quizService
     * @param QuizQuestionsService $quizQuestionsService
     * @param Request $request
     * @param RequestToQuizQuestionAnswerDTOTransformer $answerDTOTransformer
     * @return Response
     */
    #[Route('/quiz/{id}/question/{step}', name: 'quiz_questions', methods: ['GET'])]
    public function quizQuestion(
        QuizService $quizService,
        QuizQuestionsService $quizQuestionsService,
        Request $request,
        RequestToQuizQuestionAnswerDTOTransformer $answerDTOTransformer
    ): Response
    {
        $quizQuestionAnswerDTO = $answerDTOTransformer->transform($request);
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

        return $this->render('question/index.html.twig',
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
     * @param Request $request
     * @param QuizQuestionsService $quizQuestionsService
     * @param QuizQuestionAnswersService $quizQuestionAnswersService
     * @param RequestToQuizQuestionAnswerDTOTransformer $answerDTOTransformer
     * @param int $id
     * @param int $step
     * @return RedirectResponse|Response
     * @throws NonUniqueResultException
     */
    #[Route('/quiz/{id}/question/{step}', name: 'question_answers')]
    public function editAnswerQuestion(
        Request $request,
        QuizQuestionsService $quizQuestionsService,
        QuizQuestionAnswersService $quizQuestionAnswersService,
        RequestToQuizQuestionAnswerDTOTransformer $answerDTOTransformer,
        int $id,
        int $step
    ): RedirectResponse|Response
    {
        $quizQuestionAnswerDTO = $answerDTOTransformer->transform($request);

        if (! $quizQuestionAnswerDTO->isCustomerSelectedAnswer()) {
            return $this->redirectToRoute('quiz_questions', ['id' => $id, 'step' => $step]);
        }

        $answer = $quizQuestionsService->getAnswer($quizQuestionAnswerDTO);
        $quizQuestionAnswersService->save($quizQuestionAnswerDTO, $answer->isCorrect());
        $totalQuestions = $quizQuestionsService->getTotalQuestions($quizQuestionAnswerDTO->getQuizId());

        return $this->render('question/answer/index.html.twig',
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

    /**
     * @param QuizQuestionAnswersService $quizQuestionAnswersService
     * @param QuizQuestionsService $quizQuestionsService
     * @param QuizService $quizService
     * @param int $id
     * @return Response
     */
    #[Route('/quiz/{id}/result', name: 'quiz_result', methods: ["GET"])]
    public function quizResult(
        QuizQuestionAnswersService $quizQuestionAnswersService,
        QuizQuestionsService $quizQuestionsService,
        QuizService $quizService,
        int $id): Response
    {
        $quiz = $quizService->getQuizById($id);
        if (! $quiz) {
            throw $this->createNotFoundException(
                'No quiz found for id ' . $id
            );
        }

        return $this->render('question/answer/result.html.twig',
            [
                'totalCorrectAnswers' => $quizQuestionAnswersService->getTotalCorrectAnswers($id),
                'totalQuestions' => $quizQuestionsService->getTotalQuestions($id),
                'quiz' => $quizService->getQuizById($id)
            ]
        );
    }

    /**
     * @param Request $request
     * @param MainQuizService $mainQuizService
     * @return RedirectResponse|Response
     */
    #[Route('/quiz/new', name: 'quiz_new')]
    public function createQuiz(Request $request, MainQuizService $mainQuizService): RedirectResponse|Response
    {
        $secretToken = $this->getParameter('app.secretToken');
        $form = $this->createForm(QuizType::class, options: ['hiddenToken' => $secretToken]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && (! $form->has('token') || $form->has('token' !== $secretToken))) {
            return $this->redirectToRoute('quiz_new');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $mainQuizService->createQuiz($task);
            return $this->redirectToRoute('quiz_success');
        }

        return $this->renderForm('quiz/create/index.html.twig', ['formQuiz' => $form]);
    }

    #[Route('/quiz/created', name: 'quiz_success')]
    public function createdQuiz(): Response
    {
        return $this->renderForm('quiz/created/index.html.twig');
    }
}
