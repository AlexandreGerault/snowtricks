<?php

declare(strict_types=1);

namespace App\Tests\Functional\Security;

use App\Infrastructure\Security\Entity\User;
use App\Infrastructure\Security\Repository\UserRepository;
use App\Tests\Functional\Trick\Fixture\LoadUserFixture;
use App\Tests\Setup\WebTestCase;
use App\UserInterface\Security\Controller\LogoutController;
use LogicException;
use Symfony\Component\HttpFoundation\Session\Session;

class LogoutTest extends WebTestCase
{
    /** @test */
    public function itCannotLogoutUserWithoutCsrfToken(): void
    {
        $this->databaseTool->loadFixtures([LoadUserFixture::class]);

        /** @var UserRepository $userRepository */
        $userRepository = $this->client->getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => 'user@email.fr']);
        assert($user instanceof User);

        $this->client->loginUser($user);
        $this->client->request('GET', '/logout');

        /** @var Session $session */
        $session = $this->client->getContainer()->get('session');
        $this->assertNotNull($session->get('_security_main'));
    }

    public function testItCannotExecuteLogoutControllerAction(): void
    {
        $this->expectException(LogicException::class);

        $logoutController = new LogoutController();
        $logoutController->logout();
    }
}
