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
    public function __construct(private ActivateAccount $activateAccount, private Configuration $jwtConfiguration)
    {
    }

    #[Route('/confirmation-inscription', name: 'app_registration_confirmation')]
    public function __invoke(Request $request): Response
    {
        assert(is_string($request->query->get('token')));
        $token = $this->jwtConfiguration->parser()->parse($request->query->get('token'));
        assert($token instanceof UnencryptedToken);

        $email = $token->claims()->get('uid');
        $activateAccountRequest = new ActivateAccountRequest($email);

        $this->activateAccount->execute($activateAccountRequest, $this);

        return $this->redirectToRoute('app_home');
    }

    public function presents(ActivateAccountResponse $response): void
    {
        // TODO: Implement presents() method.
    }

    public function handleUserNotFound(): void
    {
        // TODO: Implement handleUserNotFound() method.
    }
}
