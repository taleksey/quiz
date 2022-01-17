<?php

declare(strict_types=1);

namespace App\Presentation\Transformers;

use App\Presentation\DTO\QuizQuestionAnswerRequestDTO;
use Symfony\Component\HttpFoundation\Request;

class QuizQuestionAnswerDTOTransformer
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return QuizQuestionAnswerRequestDTO
     */
    public function transform(): QuizQuestionAnswerRequestDTO
    {
        $step = (int) $this->request->get('step');
        $array = [
            'quizId' => (int) $this->request->get('id'),
            'step' => $step,
            'answerId' => (int) $this->request->get('question_' . $step)
        ];

        return new QuizQuestionAnswerRequestDTO($array);
    }
}
