<?php

declare(strict_types=1);

namespace App\UserInterface\Security\Controller;

use App\UserInterface\Security\Forms\ChangePasswordFormType;
use App\UserInterface\Security\ViewModels\HtmlChangePasswordViewModel;
use Domain\Security\UseCases\ChangePassword\ChangePassword;
use Domain\Security\UseCases\ChangePassword\ChangePasswordPresenterInterface;
use Domain\Security\UseCases\ChangePassword\ChangePasswordRequest;
use Domain\Security\UseCases\ChangePassword\ChangePasswordResponse;
use Lcobucci\JWT\Configuration;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChangePasswordController extends AbstractController implements ChangePasswordPresenterInterface
{
    private ?string $redirectUrl = null;

    public function __construct(private Configuration $jwtConfiguration, private HtmlChangePasswordViewModel $vm)
    {
    }

    #[Route('/changer-de-mot-de-passe', name: 'app_change_password')]
    public function __invoke(Request $request, ChangePassword $useCase): Response
    {
        $changePasswordRequest = new ChangePasswordRequest();

        $form = $this->createForm(ChangePasswordFormType::class, $changePasswordRequest);
        $this->vm->form = $form->createView();

        if (!$request->get('token')) {
            $this->vm->errors[] = "Aucun jeton valide dans l'url";

            return $this->render('security/change_password.html.twig', ['vm' => $this->vm]);
        }

        $token = $this->jwtConfiguration->parser()->parse($request->query->get('token'));
        $email = $token->claims()->get('uid');
        $changePasswordRequest->email = $email;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $useCase->execute($changePasswordRequest, $this);

            if ($this->redirectUrl) {
                return $this->redirect($this->redirectUrl);
            }
        }

        return $this->render('security/change_password.html.twig', ['vm' => $this->vm]);
    }

    public function presents(ChangePasswordResponse $response): void
    {
        $this->redirectUrl = $this->generateUrl('app_home');
    }

    public function handleUserNotFound(): void
    {
        $this->vm->errors[] = 'Aucun utilisateur correspondant';
    }
}
