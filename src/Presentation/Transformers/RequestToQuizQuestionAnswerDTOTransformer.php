<?php

namespace App\Presentation\Transformers;

use App\Presentation\DTO\QuizQuestionAnswerDTO;
use Symfony\Component\HttpFoundation\Request;

class RequestToQuizQuestionAnswerDTOTransformer
{
    /**
     * @param Request $request
     * @return QuizQuestionAnswerDTO
     */
    public function transform(Request $request): QuizQuestionAnswerDTO
    {
        $step = (int) $request->get('step');
        $array = [
            'quizId' => (int) $request->get('id'),
            'step' => $step,
            'answerId' => (int) $request->get('question_' . $step)
        ];

        return new QuizQuestionAnswerDTO($array);
    }
}
