<?php

declare(strict_types=1);

namespace App\Infrastructure\Security\Providers;

use App\Infrastructure\Security\Contracts\Factory\JwtTokenFactoryInterface;
use App\Infrastructure\Security\Contracts\Repository\MembersRepositoryInterface;
use App\Infrastructure\Security\Entity\ActivationToken;
use App\Infrastructure\Security\Entity\AskNewPasswordToken;
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
        private JwtTokenFactoryInterface $tokenFactory,
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
        $token = $this->tokenFactory->createActivationTokenForUser($user);

        $this->storeActivationToken($token);

        $this->mailer->send(
            (new Email())
                ->from("no-reply@snowtricks")
                ->to($member->email())
                ->text($this->getRegistrationText($token->getToken()))
        );
    }

    private function getRegistrationText(string $token): string
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

    private function storeActivationToken(ActivationToken $token): void
    {
        $this->em->persist($token);
        $this->em->flush();
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendNewPasswordRequest(Member $member): void
    {
        $user = $this->membersRepository->getUserByEmail($member->email());
        $token = $this->tokenFactory->createNewPasswordTokenForUser($user);

        $this->storeAskNewPasswordToken($token);

        $this->mailer->send(
            (new Email())
                ->from("no-reply@snowtricks")
                ->to($member->email())
                ->text($this->getNewPasswordText($token->getToken()))
        );
    }

    private function getNewPasswordText(string $token): string
    {
        $message = "Votre demande de nouveau mot de passe a bien été prise en compte.\n";
        $message .= "Veuillez vous rendre à l'adresse suivante : ";
        $message .= $this->urlGenerator->generate(
            'app_ask_new_password',
            ['token' => $token],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return $message;
    }

    private function storeAskNewPasswordToken(AskNewPasswordToken $token): void
    {
        $this->em->persist($token);
        $this->em->flush();
    }
}
