<?php

declare(strict_types=1);

namespace App\Tests\Functionnal\Trick;

use App\Tests\Functionnal\Trick\Fixture\ListTricksFixture;
use App\Tests\Setup\WebTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;

class HomepageTestCaseTest extends WebTestCase
{
    protected AbstractDatabaseTool $databaseTool;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var DatabaseToolCollection $databaseTool */
        $databaseTool = $this->container()->get(DatabaseToolCollection::class);
        $this->databaseTool = $databaseTool->get();
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
