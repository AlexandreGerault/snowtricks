<?php

declare(strict_types=1);

namespace App\Tests\Functional\Trick;

use App\Infrastructure\Security\Entity\User;
use App\Infrastructure\Security\Repository\UserRepository;
use App\Infrastructure\Trick\Entity\Trick;
use App\Infrastructure\Trick\Repository\TrickRepository;
use App\Tests\Functional\Security\Fixture\LoadHashedPasswordUserFixture;
use App\Tests\Functional\Trick\Fixture\ListTricksFixture;
use App\Tests\Functional\Trick\Fixture\LoadUserFixture;
use App\Tests\Setup\WebTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Component\DomCrawler\Field\FormField;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

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
        $this->client->request(Request::METHOD_GET, '/figures/ajouter');

        $this->assertResponseRedirects('/login');
    }

    public function testItShowsAFormToAnAuthenticatedUser(): void
    {
        $this->databaseTool->loadFixtures([LoadUserFixture::class]);

        /** @var UserRepository $userRepository */
        $userRepository = $this->getContainer()->get(UserRepository::class);

        /** @var User $user */
        $user = $userRepository->findOneBy(['username' => 'user']);

        $this->client->loginUser($user);

        $this->client->request(Request::METHOD_GET, '/figures/ajouter');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testItRedirectsAnUnauthenticatedUserToLoginForm(): void
    {
        $this->databaseTool->loadFixtures([LoadHashedPasswordUserFixture::class]);

        $this->client->request(Request::METHOD_GET, '/figures/ajouter');

        $this->assertResponseRedirects('/login');

        $this->client->followRedirect();

        $this->client->submitForm('Se connecter', [
            'email' => 'user@email.fr',
            'password' => 'password',
        ]);

        /** @var Session $session */
        $session = $this->client->getContainer()->get('session');
        $this->assertIsObject(unserialize($session->get('_security_main')));

        $this->assertResponseRedirects('/');
    }

    public function testItStoresTrickIfFormIsValid(): void
    {
        $this->databaseTool->loadFixtures([
            ListTricksFixture::class,
            LoadHashedPasswordUserFixture::class,
        ]);

        /** @var UserRepository $userRepository */
        $userRepository = $this->getContainer()->get(UserRepository::class);

        /** @var User $user */
        $user = $userRepository->findOneBy(['username' => 'user']);

        $this->client->loginUser($user);

        $crawler = $this->client->request(Request::METHOD_GET, '/figures/ajouter');

        $form = $crawler->filter('form[name="register_new_trick_form"]')->form();

        $csrfTokenField = $form->get("register_new_trick_form[_token]");
        assert($csrfTokenField instanceof FormField);
        $csrfToken = $csrfTokenField->getValue();

        ob_start();
        $myTempFile = tmpfile();
        assert(false !== $myTempFile);
        $image = imagecreatetruecolor(1, 1);
        assert(false !== $image);
        imagejpeg($image);
        $content = ob_get_clean();
        assert(false !== $content);
        fwrite($myTempFile, $content);

        $file = new UploadedFile(
            path: stream_get_meta_data($myTempFile)['uri'],
            originalName: 'originalName.jpg',
            mimeType: 'image/jpeg',
            test: true
        );

        $formData = [
            'register_new_trick_form' => [
                '_token' => $csrfToken,
                'name' => '360 high',
                'description' => 'Super saut où on tourne sur soi-même',
                'category' => 1,
                'videos' => ['https://dailymotion.fr'],
            ],
        ];

        $fileData = [
            'register_new_trick_form' => [
                'thumbnail' => $file,
                'illustrations' => [$file],
            ],
        ];

        $this->client->request(Request::METHOD_POST, '/figures/ajouter', $formData, $fileData);

        $this->assertResponseRedirects('/');

        /** @var TrickRepository $trickRepository */
        $trickRepository = $this->getContainer()->get(TrickRepository::class);

        /** @var ?Trick $trick */
        $trick = $trickRepository->findOneBy(['name' => '360 high']);

        $this->assertInstanceOf(Trick::class, $trick);
    }
}
