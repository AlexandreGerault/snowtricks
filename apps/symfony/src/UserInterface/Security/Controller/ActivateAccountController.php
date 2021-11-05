<?php

declare(strict_types=1);

namespace App\UserInterface\Security\Controller;

use Domain\Security\UseCases\ActivateAccount\ActivateAccount;
use Domain\Security\UseCases\ActivateAccount\ActivateAccountPresenterInterface;
use Domain\Security\UseCases\ActivateAccount\ActivateAccountRequest;
use Domain\Security\UseCases\ActivateAccount\ActivateAccountResponse;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\UnencryptedToken;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActivateAccountController extends AbstractController implements ActivateAccountPresenterInterface
{
    public function __construct(
        private ActivateAccount $activateAccount,
        private Configuration $jwtConfiguration,
        private string $redirectUrl = '',
        private array $errors = []
    ) {
    }

    #[Route('/confirmation-inscription', name: 'app_registration_confirmation')]
    public function __invoke(Request $request): Response
    {
        $tokenString = (string) $request->query->get('token');
        if (!$tokenString) {
            return new Response('No email found in the token', Response::HTTP_BAD_REQUEST);
        }

        $token = $this->jwtConfiguration->parser()->parse($tokenString);
        assert($token instanceof UnencryptedToken);

        /** @var string $email */
        $email = $token->claims()->get('uid');
        if (!$email) {
            return new Response('No email found in the token', Response::HTTP_BAD_REQUEST);
        }

        $activateAccountRequest = new ActivateAccountRequest($email);
        $this->activateAccount->execute($activateAccountRequest, $this);

        if ($this->hasErrors()) {
            $this->addFlash('errors', $this->errors);
        }

        return $this->redirect($this->redirectUrl);
    }

    public function presents(ActivateAccountResponse $response): void
    {
        $this->redirectUrl = '/';
    }

    public function handleUserNotFound(): void
    {
        $this->errors[] = 'Aucun utilisateur ne semble correspondre';
        $this->redirectUrl = '/';
    }

    private function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }
}
