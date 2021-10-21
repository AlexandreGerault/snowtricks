<?php

declare(strict_types=1);

namespace App\Tests\Functionnal\Trick;

use App\Infrastructure\Security\Entity\User;
use App\Infrastructure\Security\Repository\UserRepository;
use App\Tests\Functionnal\Trick\Fixture\ListTricksFixture;
use App\Tests\Functionnal\Trick\Fixture\LoadUserFixture;
use App\Tests\Setup\WebTestCase;
use Domain\Security\Gateway\MembersGateway;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Component\HttpFoundation\Response;

class RegisterNewTrickTest extends WebTestCase
{
    protected AbstractDatabaseTool $databaseTool;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var DatabaseToolCollection $databaseTool */
        $databaseTool = $this->getContainer()->get(DatabaseToolCollection::class);
        $this->databaseTool = $databaseTool->get();
    }

    public function testItRedirectsUnauthenticatedUsersToLoginForm(): void
    {
        $this->databaseTool->loadFixtures([ListTricksFixture::class]);
        $this->client->request('GET', '/figures/ajouter');

        $this->assertResponseRedirects('/login');
    }

    public function testItShowsAFormToAnAuthenticatedUser(): void
    {
        $this->databaseTool->loadFixtures([LoadUserFixture::class]);

        /** @var UserRepository $gateway */
        $gateway = $this->getContainer()->get(MembersGateway::class);

        /** @var User $user */
        $user = $gateway->findOneBy(['username' => 'user']);

        $this->client->loginUser($user);

        $this->client->request('GET', '/figures/ajouter');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
