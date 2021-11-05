<?php

declare(strict_types=1);

namespace App\Tests\Functional\Security;

use App\Infrastructure\Security\Repository\UserRepository;
use App\Tests\Functional\Security\Fixture\LoadUserFixture;
use App\Tests\Setup\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class RegisterTest extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->databaseTool->loadFixtures([LoadUserFixture::class]);
    }

    public function testIsShowsTheFormToAnUnauthenticatedUser(): void
    {
        $this->client->request('GET', '/inscription');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testAUserCanSubmitTheRegisterFormAndItCreatesAnInactiveAccount(): void
    {
        $this->client->request('GET', '/inscription');

        $this->client->submitForm("S'inscrire", [
            'registration_form[email]' => 'email@email.fr',
            'registration_form[username]' => 'username',
            'registration_form[password][first]' => 'password',
            'registration_form[password][second]' => 'password',
        ]);

        /** @var UserRepository $userRepository */
        $userRepository = $this->getContainer()->get(UserRepository::class);
        $createdUser = $userRepository->findOneBy(['email' => 'email@email.fr']);

        $this->assertEmailCount(1);
        $this->assertEquals('email@email.fr', $createdUser?->getEmail());
        $this->assertEquals('username', $createdUser?->getUsername());
        $this->assertFalse($createdUser?->isActive());
    }

    public function testItCannotRegisterIfEmailIsAlreadyInUse(): void
    {
        $this->client->followRedirects();
        $this->client->request('GET', '/inscription');

        $crawler = $this->client->submitForm('registerButton', [
            'registration_form[email]' => 'user@email.fr',
            'registration_form[username]' => 'username2',
            'registration_form[password][first]' => 'passwordpassword',
            'registration_form[password][second]' => 'passwordpassword',
        ]);

        $this->assertStringContainsString('Cette adresse mail est déjà utilisée', $crawler->html());
    }

    public function testItCannotRegisterIfUsernameIsAlreadyInUse(): void
    {
        $this->client->followRedirects();
        $this->client->request('GET', '/inscription');

        $crawler = $this->client->submitForm('registerButton', [
            'registration_form[email]' => 'user2@email.fr',
            'registration_form[username]' => 'user',
            'registration_form[password][first]' => 'password',
            'registration_form[password][second]' => 'password',
        ]);

        $this->assertStringContainsString("Ce nom d'utilisateur est déjà utilisé", $crawler->html());
    }
}
