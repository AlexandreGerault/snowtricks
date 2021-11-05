<?php

namespace App\Tests\Functional\Security;

use App\Tests\Functional\Security\Fixture\LoadUserFixture;
use App\Tests\Setup\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class AskNewPasswordTest extends WebTestCase
{
    /** @test */
    public function itSendAnEmailWithTokenToUser(): void
    {
        $this->databaseTool->loadFixtures([LoadUserFixture::class]);

        $this->client->request(Request::METHOD_GET, '/nouveau-mot-de-passe');

        $this->client->submitForm('Valider la demande', ['ask_new_password_form[email]' => 'user@email.fr']);

        $this->assertEmailCount(1);
    }

    /** @test */
    public function itDoesNotSendAnEmailToNonExistingUser(): void
    {
        $this->databaseTool->loadFixtures([LoadUserFixture::class]);

        $this->client->request(Request::METHOD_GET, '/nouveau-mot-de-passe');

        $this->client->submitForm('Valider la demande', ['ask_new_password_form[email]' => 'user@email.de']);

        $this->assertEmailCount(0);
    }
}
