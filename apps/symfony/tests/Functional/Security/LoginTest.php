<?php

namespace App\Tests\Functional\Security;

use App\Infrastructure\Security\Entity\User;
use App\Infrastructure\Security\Repository\UserRepository;
use App\Tests\Functional\Security\Fixture\LoadHashedPasswordUserFixture;
use App\Tests\Functional\Trick\Fixture\LoadUserFixture;
use App\Tests\Setup\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class LoginTest extends WebTestCase
{
    /** @test */
    public function itRedirectsAnAuthenticatedUser(): void
    {
        $this->databaseTool->loadFixtures([LoadUserFixture::class]);

        /** @var UserRepository $userRepository */
        $userRepository = $this->client->getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => 'user@email.fr']);
        assert($user instanceof User);

        $this->client->loginUser($user);
        $this->client->request('GET', '/login');

        $this->assertResponseRedirects('/');
    }

    /** @test */
    public function itShowsTheLoginFormToAnUnauthenticatedUser(): void
    {
        $this->client->request('GET', '/login');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /** @test */
    public function itCanLogsAUserWithCorrectCredentials(): void
    {
        $this->databaseTool->loadFixtures([LoadHashedPasswordUserFixture::class]);

        $this->client->request('GET', '/login');

        $this->client->submitForm('Se connecter', [
            'email' => 'user@email.fr',
            'password' => 'password',
        ]);

        /** @var Session $session */
        $session = $this->client->getContainer()->get('session');
        $this->assertIsObject(unserialize($session->get('_security_main')));
    }

    /** @test */
    public function itDisplaysErrorsIfUserProvidesWrongCredentials(): void
    {
        $this->databaseTool->loadFixtures([LoadHashedPasswordUserFixture::class]);

        $this->client->followRedirects();
        $this->client->request('GET', '/login');

        $crawler = $this->client->submitForm('Se connecter', [
            'email' => 'user@email.fr',
            'password' => 'wrongpassword',
        ]);

        $this->assertStringContainsString('Les informations de connexions ne correspondent pas.', $crawler->html());
    }

    /** @test */
    public function itDisplaysErrorsIfUserDoesNotExist(): void
    {
        $this->databaseTool->loadFixtures([LoadHashedPasswordUserFixture::class]);

        $this->client->followRedirects();
        $this->client->request('GET', '/login');

        $crawler = $this->client->submitForm('Se connecter', [
            'email' => 'user@email.de',
            'password' => 'wrongpassword',
        ]);

        $this->assertStringContainsString('Aucun utilisateur trouvé.', $crawler->html());
    }
}
