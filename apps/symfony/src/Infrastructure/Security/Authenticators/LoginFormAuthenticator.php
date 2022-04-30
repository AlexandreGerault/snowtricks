<?php

declare(strict_types=1);

namespace App\Infrastructure\Security\Authenticators;

use App\UserInterface\Security\ViewModels\HtmlLoginViewModel;
use Domain\Security\UseCases\Login\Login;
use Domain\Security\UseCases\Login\LoginPresenterInterface;
use Domain\Security\UseCases\Login\LoginRequest;
use Domain\Security\UseCases\Login\LoginResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator implements LoginPresenterInterface
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private HtmlLoginViewModel $viewModel;

    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private Login $login
    ) {
        $this->viewModel = new HtmlLoginViewModel();
    }

    public function authenticate(Request $request): Passport
    {
        $email = (string) $request->request->get('email', '');
        $password = (string) $request->request->get('password', '');
        $csrfToken = (string) $request->request->get('_csrf_token', '');

        $loginRequest = new LoginRequest($email, $password);
        $this->login->execute($loginRequest, $this);

        $request->getSession()->set(Security::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($password),
            [
                new CsrfTokenBadge('authenticate', $csrfToken),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        if ($request->hasSession()) {
            $request->getSession()->set(
                Security::AUTHENTICATION_ERROR,
                array_merge($this->viewModel->errors, [$exception->getMessage()])
            );
        }

        $url = $this->getLoginUrl($request);

        return new RedirectResponse($url);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }

    public function presents(LoginResponse $response): void
    {
        $errors = $response->getErrors();

        if (in_array('UserNotFound', $errors)) {
            $this->viewModel->errors[] = 'Aucun utilisateur trouvé.';
        }

        if (in_array('WrongCredentials', $errors)) {
            $this->viewModel->errors[] = 'Les informations de connexions ne correspondent pas.';
        }
    }
}
