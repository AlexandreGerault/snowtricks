<?php

declare(strict_types=1);

namespace App\Infrastructure\Security\Repository;

use App\Infrastructure\Security\Contracts\Repository\MembersRepositoryInterface;
use App\Infrastructure\Security\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Domain\Security\Entity\Member;
use Domain\Security\Exceptions\UserNotFoundException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface, MembersRepositoryInterface
{
    public function __construct(ManagerRegistry $registry, private UserPasswordHasherInterface $hasher)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if ( ! $user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function getUserByEmail(string $email): ?User
    {
        return $this->createQueryBuilder("m")
                    ->where('m.email = :email')
                    ->setParameter('email', $email)
                    ->getQuery()
                    ->getOneOrNullResult();
    }

    public function getMemberByEmail(string $email): Member
    {
        $user = $this->getUserByEmail($email);

        if ( ! $user) {
            throw new UserNotFoundException();
        }

        return new Member($user->getEmail(), $user->getUsername(), $user->getPassword());
    }

    public function checkEmailIsFree(string $email): bool
    {
        return (bool) $this->createQueryBuilder('m')
                           ->where('m.email = :email')
                           ->setParameter('email', $email)
                           ->getFirstResult();
    }

    public function checkUsernameIsFree(string $username): bool
    {
        return (bool) $this->createQueryBuilder('m')
                           ->where('m.username = :username')
                           ->setParameter('username', $username)
                           ->getFirstResult();
    }

    public function register(Member $member): void
    {
        $user = new User();
        $user->setEmail($member->email());
        $user->setUsername($member->username());
        $user->setPassword($this->hasher->hashPassword($member->password()));

        $this->_em->persist($user);
        $this->_em->flush();
    }
}
