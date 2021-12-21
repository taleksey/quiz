<?php

namespace App\Presentation\Controller;

use App\Domain\Quiz\Service\QuizQuestionAnswersService;
use App\Domain\Quiz\Service\QuizQuestionsService;
use App\Domain\Quiz\Service\QuizService;
use App\Presentation\DTO\QuizQuestionAnswerDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

class QuizController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     * @param QuizService $quizService
     * @return Response
     */
    public function index(QuizService $quizService): Response
    {
        $quizzes = $quizService->getAllQuizzes();

        return $this->render('quizzes/index.html.twig', ['quizzes' => $quizzes]);
    }

    /**
     * @Route("/quiz/{id}", name="quiz_show", requirements={"id"="\d+"})
     * @param QuizService $quizService
     * @param int $id
     * @return Response
     */
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
     * @Route("/quiz/{id}/question/{step}", name="quiz_questions", methods={"GET"}, requirements={"id"="\d+", "step"="\d+"})
     * @param QuizService $quizService
     * @param QuizQuestionsService $quizQuestionsService
     * @param Request $request
     * @return Response
     */
    public function quizQuestion(
        QuizService $quizService,
        QuizQuestionsService $quizQuestionsService,
        Request $request,
    ): Response
    {
        $quizQuestionAnswerDTO = new QuizQuestionAnswerDTO($request);
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
     * @Route("/quiz/{id}/question/{step}", name="question_answers", methods={"POST"}, requirements={"id"="\d+", "step"="\d+"})
     * @param Request $request
     * @param QuizQuestionsService $quizQuestionsService
     * @param QuizQuestionAnswersService $quizQuestionAnswersService
     * @param int $id
     * @param int $step
     * @return RedirectResponse|Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function answerQuestion(
        Request $request,
        QuizQuestionsService $quizQuestionsService,
        QuizQuestionAnswersService $quizQuestionAnswersService,
        int $id,
        int $step
    ): RedirectResponse|Response
    {
        $quizQuestionAnswerDTO = new QuizQuestionAnswerDTO($request);

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
     * @Route("/quiz/{id}/result", name="quiz_result", methods={"GET"}, requirements={"id"="\d+"})
     */
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
}
