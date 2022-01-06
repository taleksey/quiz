<?php

namespace App\Presentation\Transformers;

use App\Presentation\DTO\RequestQuizDTO;
use Symfony\Component\HttpFoundation\Request;

interface RequestTransformer
{
    public function __construct(Request $request);

    public function transform(): RequestQuizDTO;
}
