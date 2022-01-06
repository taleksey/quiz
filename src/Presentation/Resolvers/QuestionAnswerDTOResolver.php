<?php

namespace App\Presentation\Resolvers;

use App\Presentation\DTO\RequestQuizDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use ReflectionClass;
use ReflectionException;

class QuestionAnswerDTOResolver implements ArgumentValueResolverInterface
{
    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return bool
     */
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        try {
            $reflection = new ReflectionClass($argument->getType());
            return $reflection->implementsInterface(RequestQuizDTO::class);
        } catch (ReflectionException){}

        return false;
    }

    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return iterable
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $class = str_replace('\DTO\\', '\Transformers\\', $argument->getType()) . 'Transformer';

        $dto = new $class($request);

        yield $dto->transform();
    }
}
