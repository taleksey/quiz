<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Infractructure\Service\AuthService;
use App\Presentation\Form\Auth\AuthorizationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    #[Route('/auth', name: 'authorize')]
    public function authorize(Request $request, AuthService $authService): RedirectResponse|Response
    {
        $authorizationKey = $this->getParameter('app.authorizationKey');
        $form = $this->createForm(AuthorizationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (! $authService->authorize($form->getData())) {
                $this->addFlash('error', 'We were not able to authorize. Please check if you inputted correct token.');
                return $this->redirect($request->getUri());
            }

            return $this->render('auth/success_authorization_quiz.html.twig');
        }

        return $this->renderForm('auth/index.html.twig', ['form' => $form]);
    }
}
