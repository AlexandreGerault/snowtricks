<?php

declare(strict_types=1);

namespace App\UserInterface\Security\Controller;

use App\UserInterface\Security\Forms\AskNewPasswordFormType;
use App\UserInterface\Security\ViewModels\HtmlAskNewPasswordViewModel;
use Domain\Security\UseCases\AskNewPassword\AskNewPassword;
use Domain\Security\UseCases\AskNewPassword\AskNewPasswordPresenterInterface;
use Domain\Security\UseCases\AskNewPassword\AskNewPasswordRequest;
use Domain\Security\UseCases\AskNewPassword\AskNewPasswordResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AskNewPasswordController extends AbstractController implements AskNewPasswordPresenterInterface
{
    public function __construct(private HtmlAskNewPasswordViewModel $vm)
    {
    }

    #[Route('/nouveau-mot-de-passe', name: 'app_ask_new_password')]
    public function __invoke(Request $request, AskNewPassword $useCase): Response
    {
        $askNewPasswordRequest = new AskNewPasswordRequest();

        $form = $this->createForm(AskNewPasswordFormType::class, $askNewPasswordRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $useCase->execute($askNewPasswordRequest, $this);

            if ($this->vm->redirect) {
                return $this->redirect($this->vm->redirect);
            }
        }

        $this->vm->form = $form->createView();

        return $this->render('security/ask_new_password.html.twig', ['vm' => $this->vm]);
    }

    public function presents(AskNewPasswordResponse $response): void
    {
        $this->vm->redirect = $this->generateUrl('app_home');
    }

    public function handleUserNotFound(): void
    {
        $this->vm->errors[] = 'Cette adresse mail ne correspond à aucun utilisateur connu';
    }
}
