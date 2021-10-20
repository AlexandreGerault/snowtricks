<?php

declare(strict_types=1);

namespace App\Tests\Application\Trick;

use App\Tests\Application\Trick\Fixture\ListTricksFixture;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomepageTestCaseTest extends WebTestCase
{
    protected AbstractDatabaseTool $databaseTool;
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->databaseTool = $this->getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function testHomepageDisplaysTricks(): void
    {
        $this->databaseTool->loadFixtures([ListTricksFixture::class]);
        $crawler = $this->client->request('GET', '/');

        $this->assertSelectorTextContains('h1', 'Snowtricks');
        $this->assertStringContainsString('Trick 0', $crawler->html());
        $this->assertStringContainsString('Trick 14', $crawler->html());
        $this->assertResponseIsSuccessful();
    }
}
