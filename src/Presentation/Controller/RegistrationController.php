<?php

namespace App\Presentation\Controller;

use App\Domain\Customer\Entity\Customer;
use App\Domain\Customer\Service\CustomerService;
use App\Presentation\DTO\Customer\CustomerDTO;
use App\Presentation\Form\Registration\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/registration', name: 'registration')]
    public function index(Request $request, CustomerService $registrationService): Response
    {
        $customer = new CustomerDTO();
        $form = $this->createForm(RegistrationFormType::class, $customer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('plainPassword')->getData();
            $registrationService->registrationCustomer($form->getData(), $password);

            return $this->redirectToRoute('app_login');
        }
        return $this->render('registration/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
