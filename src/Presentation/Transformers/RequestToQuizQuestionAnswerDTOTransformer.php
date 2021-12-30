<?php

namespace App\Presentation\Transformers;

use App\Presentation\DTO\QuizQuestionAnswerRequestDTO;
use Symfony\Component\HttpFoundation\Request;

class RequestToQuizQuestionAnswerDTOTransformer
{
    /**
     * @param Request $request
     * @return QuizQuestionAnswerRequestDTO
     */
    public function transform(Request $request): QuizQuestionAnswerRequestDTO
    {
        $step = (int) $request->get('step');
        $array = [
            'quizId' => (int) $request->get('id'),
            'step' => $step,
            'answerId' => (int) $request->get('question_' . $step)
        ];

        return new QuizQuestionAnswerRequestDTO($array);
    }
}
