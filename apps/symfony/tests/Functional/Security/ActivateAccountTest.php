<?php

declare(strict_types=1);

namespace App\Tests\Functional\Security;

use App\Infrastructure\Security\Contracts\Factory\JwtTokenFactoryInterface;
use App\Infrastructure\Security\Entity\User;
use App\Infrastructure\Security\Repository\UserRepository;
use App\Tests\Functional\Security\Fixture\LoadUserFixture;
use App\Tests\Setup\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ActivateAccountTest extends WebTestCase
{
    public function testItActivatesAnAccountWhenTheTokenIsValid(): void
    {
        $this->databaseTool->loadFixtures([LoadUserFixture::class]);

        /** @var UserRepository $userRepository */
        $userRepository = $this->getContainer()->get(UserRepository::class);
        /** @var User $inactiveUser */
        $inactiveUser = $userRepository->findOneBy(['username' => 'inactive']);

        /** @var JwtTokenFactoryInterface $tokenFactory */
        $tokenFactory = $this->getContainer()->get(JwtTokenFactoryInterface::class);
        $token = $tokenFactory->createActivationTokenForUser($inactiveUser);

        /** @var EntityManagerInterface $em */
        $em = $this->getContainer()->get(EntityManagerInterface::class);
        $em->persist($token);
        $em->flush();

        $this->client->request('GET', '/confirmation-inscription?token='.$token->getToken());

        /** @var User $activeUser */
        $activeUser = $userRepository->findOneBy(['username' => 'inactive']);
        $this->assertTrue($activeUser->isActive());
    }

    // Vérifier le cas où le token est invalide
    /** @test */
    public function itDoesNotActivateTheAccountIfInvalidTokenIsProvided(): void
    {
        $this->databaseTool->loadFixtures([LoadUserFixture::class]);

        /** @var UserRepository $userRepository */
        $userRepository = $this->getContainer()->get(UserRepository::class);
        $userRepository->findOneBy(['username' => 'inactive']);

        $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c';
        $this->client->request('GET', '/confirmation-inscription?token='.$token);

        /** @var User $activeUser */
        $activeUser = $userRepository->findOneBy(['username' => 'inactive']);
        $this->assertFalse($activeUser->isActive());
    }

    /** @test */
    public function itAddAnErrorFlashMessageIfNoUserFound(): void
    {
        $this->databaseTool->loadFixtures([LoadUserFixture::class]);

        /** @var UserRepository $userRepository */
        $userRepository = $this->getContainer()->get(UserRepository::class);
        /** @var User $inactiveUser */
        $inactiveUser = $userRepository->findOneBy(['username' => 'inactive']);

        /** @var JwtTokenFactoryInterface $tokenFactory */
        $tokenFactory = $this->getContainer()->get(JwtTokenFactoryInterface::class);
        $token = $tokenFactory->createActivationTokenForUser($inactiveUser);

        /** @var EntityManagerInterface $em */
        $em = $this->getContainer()->get(EntityManagerInterface::class);
        $em->persist($token);
        $em->flush();

        $em->remove($inactiveUser);
        $em->flush();

        $this->client->request('GET', '/confirmation-inscription?token='.$token->getToken());

        /** @var Session $session */
        $session = $this->getContainer()->get('session');
        $this->assertTrue($session->getFlashBag()->has('errors'));
        $this->assertResponseRedirects('/');
    }

    /** @test */
    public function itReturnsAnErrorResponseIfNoTokenIsProvided(): void
    {
        $this->client->request('GET', '/confirmation-inscription');

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }
}
