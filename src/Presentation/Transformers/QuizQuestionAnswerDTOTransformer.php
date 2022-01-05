<?php

namespace App\Presentation\Transformers;

use App\Presentation\DTO\QuizQuestionAnswerDTO;
use App\Presentation\DTO\RequestQuizDTO;
use Symfony\Component\HttpFoundation\Request;

class QuizQuestionAnswerDTOTransformer implements RequestTransformer
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return QuizQuestionAnswerDTO
     */
    public function transform(): RequestQuizDTO
    {
        $step = (int) $this->request->get('step');
        $array = [
            'quizId' => (int) $this->request->get('id'),
            'step' => $step,
            'answerId' => (int) $this->request->get('question_' . $step)
        ];

        return new QuizQuestionAnswerDTO($array);
    }
}