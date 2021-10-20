<?php

declare(strict_types=1);

namespace App\Tests\Application\Trick;

use App\Tests\Application\Trick\Fixture\ListTricksFixture;
use App\Tests\Application\Trick\Fixture\LoadUserFixture;
use Domain\Security\Gateway\MembersGateway;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class RegisterNewTrickTest extends WebTestCase
{
    protected AbstractDatabaseTool $databaseTool;
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->databaseTool = $this->getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function test_it_redirects_unauthenticated_users_to_login_form(): void
    {
        $this->databaseTool->loadFixtures([ListTricksFixture::class]);
        $this->client->request('GET', '/figures/ajouter');

        $this->assertResponseRedirects('/login');
    }

    public function test_it_shows_a_form_to_an_authenticated_user(): void
    {
        $this->databaseTool->loadFixtures([LoadUserFixture::class]);
        $user = $this->getContainer()->get(MembersGateway::class)->findOneBy(['username' => 'user']);
        $this->client->loginUser($user);

        $this->client->request('GET', '/figures/ajouter');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
