<?php

declare(strict_types=1);

namespace App\Tests\Functional\Security;

use App\Infrastructure\Security\Contracts\Factory\JwtTokenFactoryInterface;
use App\Infrastructure\Security\Entity\User;
use App\Infrastructure\Security\Repository\UserRepository;
use App\Tests\Functional\Security\Fixture\LoadUserFixture;
use App\Tests\Setup\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class ChangePasswordTest extends WebTestCase
{
    /** @test */
    public function itCanChangeThePasswordOfExistingUser(): void
    {
        $this->databaseTool->loadFixtures([LoadUserFixture::class]);

        /** @var UserRepository $userRepository */
        $userRepository = $this->getContainer()->get(UserRepository::class);
        /** @var User $user */
        $user = $userRepository->findOneBy(['username' => 'user']);

        /** @var JwtTokenFactoryInterface $tokenFactory */
        $tokenFactory = $this->getContainer()->get(JwtTokenFactoryInterface::class);
        $token = $tokenFactory->createNewPasswordTokenForUser($user);

        /** @var EntityManagerInterface $em */
        $em = $this->getContainer()->get(EntityManagerInterface::class);
        $em->persist($token);
        $em->flush();

        $this->client->request(Request::METHOD_GET, '/changer-de-mot-de-passe?token='.$token->getToken());

        $this->client->submitForm('Valider le changement', [
            'change_password_form[password][first]' => 'new_password',
            'change_password_form[password][second]' => 'new_password',
        ]);

        /** @var User $updatedUser */
        $updatedUser = $userRepository->findOneBy(['username' => 'user']);
        $this->assertNotEquals($user->getPassword(), $updatedUser->getPassword());
    }

    /** @test */
    public function itRenderTheFormWithNoUserFoundErrorMessage(): void
    {
        $this->databaseTool->loadFixtures([LoadUserFixture::class]);

        /** @var UserRepository $userRepository */
        $userRepository = $this->getContainer()->get(UserRepository::class);
        /** @var User $user */
        $user = $userRepository->findOneBy(['username' => 'user']);

        /** @var JwtTokenFactoryInterface $tokenFactory */
        $tokenFactory = $this->getContainer()->get(JwtTokenFactoryInterface::class);
        $token = $tokenFactory->createNewPasswordTokenForUser($user);

        /** @var EntityManagerInterface $em */
        $em = $this->getContainer()->get(EntityManagerInterface::class);
        $em->persist($token);
        $em->flush();

        $em->remove($user);
        $em->flush();

        $this->client->request(Request::METHOD_GET, '/changer-de-mot-de-passe?token='.$token->getToken());

        $crawler = $this->client->submitForm('Valider le changement', [
            'change_password_form[password][first]' => 'new_password',
            'change_password_form[password][second]' => 'new_password',
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Aucun utilisateur correspondant', $crawler->html());
    }

    /** @test */
    public function itWritesAnErrorMessageIfNoTokenIsProvided(): void
    {
        $this->databaseTool->loadFixtures([LoadUserFixture::class]);

        $this->client->request(Request::METHOD_GET, '/changer-de-mot-de-passe');

        $crawler = $this->client->submitForm('Valider le changement', [
            'change_password_form[password][first]' => 'new_password',
            'change_password_form[password][second]' => 'new_password',
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString("Aucun jeton valide dans l'url", $crawler->html());
    }
}
