<?php

namespace App\Tests\Setup;

use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class WebTestCase extends BaseWebTestCase
{
    protected KernelBrowser $client;
    protected AbstractDatabaseTool $databaseTool;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();

        /** @var DatabaseToolCollection $databaseTool */
        $databaseTool = $this->client->getContainer()->get(DatabaseToolCollection::class);
        $this->databaseTool = $databaseTool->get();
    }

    public function generateImage(): UploadedFile
    {
        $myTempFile = tmpfile();
        $image = imagecreatetruecolor(1, 1);
        assert(false !== $image && false !== $myTempFile);
        imagejpeg($image);
        $fileContent = ob_get_clean();
        assert(false !== $fileContent);
        fwrite($myTempFile, $fileContent);

        return new UploadedFile(
            path: stream_get_meta_data($myTempFile)['uri'],
            originalName: 'originalName.jpg',
            mimeType: 'image/jpeg',
            test: true
        );
    }
}
