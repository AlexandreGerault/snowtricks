<?php

declare(strict_types=1);

namespace App\Tests\Functionnal\Security;

use App\Infrastructure\Security\Repository\UserRepository;
use App\Tests\Setup\WebTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Component\HttpFoundation\Response;

class RegisterTest extends WebTestCase
{
    protected AbstractDatabaseTool $databaseTool;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var DatabaseToolCollection $databaseTool */
        $databaseTool = $this->getContainer()->get(DatabaseToolCollection::class);
        $this->databaseTool = $databaseTool->get();
        $this->databaseTool->loadFixtures([]);
    }

    public function testIsShowsTheFormToAnUnauthenticatedUser(): void
    {
        $this->client->request('GET', '/inscription');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testAUserCanSubmitTheRegisterFormAndItCreatesAnAccount(): void
    {
        $this->client->request('GET', '/inscription');

        $crawler = $this->client->submitForm("S'inscrire", [
            'registration_form[email]' => 'email@email.fr',
            'registration_form[username]' => 'username',
            'registration_form[password][first]' => 'password',
            'registration_form[password][second]' => 'password',
        ]);

        /** @var UserRepository $userRepository */
        $userRepository = $this->getContainer()->get(UserRepository::class);
        $createdUser = $userRepository->findOneBy(['email' => 'email@email.fr']);

        $this->assertResponseRedirects("/");
        $this->assertEquals("email@email.fr", $createdUser?->getEmail());
        $this->assertEquals("username", $createdUser?->getUsername());
    }
}
