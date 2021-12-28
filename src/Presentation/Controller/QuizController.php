<?php

namespace App\Presentation\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class QuizController
{
    /**
     * @Route("/")
     */
    public function index(): Response
    {
        return new Response(
            '<html><body>Quiz</body></html>'
        );
    }
}
