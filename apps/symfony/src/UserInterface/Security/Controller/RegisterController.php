<?php

declare(strict_types=1);

namespace App\UserInterface\Security\Controller;

use App\UserInterface\Security\DTO\RegisterFormModel;
use App\UserInterface\Security\Forms\RegistrationFormType;
use App\UserInterface\Security\ViewModels\HtmlRegisterViewModel;
use Domain\Security\UseCases\Register\Register;
use Domain\Security\UseCases\Register\RegisterPresenterInterface;
use Domain\Security\UseCases\Register\RegisterRequest;
use Domain\Security\UseCases\Register\RegisterResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController implements RegisterPresenterInterface
{
    public function __construct(private HtmlRegisterViewModel $viewModel)
    {
    }

    #[Route('/inscription', name: 'app_register')]
    public function __invoke(
        Request $request,
        Register $register
    ): Response {
        $formRequest = new RegisterFormModel();
        $form = $this->createForm(RegistrationFormType::class, $formRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $registerRequest = new RegisterRequest($formRequest->username, $formRequest->email, $formRequest->password);
            $register->execute($registerRequest, $this);

            if ($this->viewModel->redirect) {
                return $this->redirect($this->viewModel->redirect);
            }
        }

        $this->viewModel->form = $form->createView();

        return $this->render('security/register.html.twig', ['vm' => $this->viewModel]);
    }

    public function handleEmailAlreadyInUse(): void
    {
        $this->viewModel->errors[] = 'Cette adresse mail est déjà utilisée.';
    }

    public function handleUsernameAlreadyInUse(): void
    {
        $this->viewModel->errors[] = "Ce nom d'utilisateur est déjà utilisé.";
    }

    public function presents(RegisterResponse $response): void
    {
        $this->viewModel->redirect = $this->generateUrl('app_home');
    }
}
