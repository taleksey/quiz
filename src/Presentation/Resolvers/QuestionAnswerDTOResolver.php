<?php

declare(strict_types=1);

namespace App\Presentation\Resolvers;

use App\Presentation\DTO\Quiz\QuestionAnswerRequestDTO;
use App\Presentation\Transformers\QuizQuestionAnswerDTOTransformer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class QuestionAnswerDTOResolver implements ArgumentValueResolverInterface
{
    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return bool
     */
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return str_contains($argument->getType(), 'QuestionAnswerRequestDTO');
    }

    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return iterable<int, QuestionAnswerRequestDTO>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $dto = new QuizQuestionAnswerDTOTransformer($request);

        yield $dto->transform();
    }
}
