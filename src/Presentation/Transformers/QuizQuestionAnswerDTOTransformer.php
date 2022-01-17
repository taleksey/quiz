<?php

declare(strict_types=1);

namespace App\Presentation\Transformers;

use App\Presentation\DTO\Quiz\QuestionAnswerRequestDTO;
use Symfony\Component\HttpFoundation\Request;

class QuizQuestionAnswerDTOTransformer
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return QuestionAnswerRequestDTO
     */
    public function transform(): QuestionAnswerRequestDTO
    {
        $step = (int) $this->request->get('step');
        $array = [
            'quizId' => (int) $this->request->get('id'),
            'step' => $step,
            'answerId' => (int) $this->request->get('question_' . $step)
        ];

        return new QuestionAnswerRequestDTO($array);
    }
}
