<?php

declare(strict_types=1);

namespace App\Infrastructure\Security\Providers;

use App\Infrastructure\Security\Contracts\Factory\ActivationTokenFactoryInterface;
use App\Infrastructure\Security\Contracts\Repository\MembersRepositoryInterface;
use App\Infrastructure\Security\Entity\ActivationToken;
use Doctrine\ORM\EntityManagerInterface;
use Domain\Security\Entity\Member;
use Domain\Security\Providers\NotificationProviderInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class NotifierProvider implements NotificationProviderInterface
{
    public function __construct(
        private MembersRepositoryInterface $membersRepository,
        private ActivationTokenFactoryInterface $tokenFactory,
        private MailerInterface $mailer,
        private EntityManagerInterface $em,
        private UrlGeneratorInterface $urlGenerator
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendRegistrationNotification(Member $member): void
    {
        $user = $this->membersRepository->getUserByEmail($member->email());
        $token = $this->tokenFactory->createForUser($user);

        $this->storeToken($token);

        $this->mailer->send(
            (new Email())
                ->from("no-reply@snowtricks")
                ->to($member->email())
                ->text($this->getEmailText($token->getToken()))
        );
    }

    private function getEmailText(string $token): string
    {
        $message = "Félicitations !\n";
        $message .= "Votre compte a été créé avec succès. Cliquez sur ce lien pour activer votre compte :\n";
        $message .= $this->urlGenerator->generate(
            'app_registration_confirmation',
            ['token' => $token],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return $message;
    }

    private function storeToken(ActivationToken $token): void
    {
        $this->em->persist($token);
        $this->em->flush();
    }
}
