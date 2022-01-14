<?php

namespace App\Presentation\Controller;

use App\Domain\Quiz\Service\AuthService;
use App\Presentation\DTO\Auth\AuthorizationDTO;
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
            /** @var AuthorizationDTO $authorizationDTO */
            $authorizationDTO = $form->getData();
            if (! $authorizationDTO->isEqual($authorizationKey)) {
                $this->addFlash('error', 'We\'ve entered wrong key');
                return $this->redirect($request->getUri());
            }
            $authService->setAuthorizationKeyForCreatingQuiz();

            return $this->render('auth/success_authorization_quiz.html.twig');
        }

        return $this->renderForm('auth/index.html.twig', ['form' => $form]);
    }
}
