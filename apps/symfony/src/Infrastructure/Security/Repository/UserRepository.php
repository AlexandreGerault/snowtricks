<?php

declare(strict_types=1);

namespace App\Infrastructure\Security\Repository;

use App\Infrastructure\Security\Contracts\Repository\MembersRepositoryInterface;
use App\Infrastructure\Security\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
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
 * @extends  ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface, MembersRepositoryInterface
{
    public function __construct(ManagerRegistry $registry, private UserPasswordHasherInterface $hasher)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     *
     * @throws ORMException
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * @throws NonUniqueResultException
     * @throws UserNotFoundException
     */
    public function getUserByEmail(string $email): User
    {
        return $this->createQueryBuilder('m')
                    ->where('m.email = :email')
                    ->setParameter('email', $email)
                    ->getQuery()
                    ->getOneOrNullResult() ?? throw new UserNotFoundException();
    }

    /**
     * @throws NonUniqueResultException
     * @throws UserNotFoundException
     */
    public function getMemberByEmail(string $email): Member
    {
        $user = $this->getUserByEmail($email);

        if (!is_string($user->getEmail())
            || !is_string($user->getUsername())
            || !is_string($user->getPassword())
        ) {
            throw new \InvalidArgumentException('Cannot create member from user');
        }

        return new Member($user->getEmail(), $user->getUsername(), $user->getPassword());
    }

    public function checkEmailIsFree(string $email): bool
    {
        $user = $this->findBy(['email' => $email]);

        return !$user;
    }

    public function checkUsernameIsFree(string $username): bool
    {
        $user = $this->findBy(['username' => $username]);

        return !$user;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function register(Member $member): void
    {
        $user = new User();
        $user->setEmail($member->email());
        $user->setUsername($member->username());
        $user->setPassword($this->hasher->hashPassword($user, $member->password()));
        $user->setActive($member->isActive());

        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * @throws OptimisticLockException
     * @throws NonUniqueResultException
     * @throws ORMException
     */
    public function updateMember(Member $member): void
    {
        $user = $this->getUserByEmail($member->email());
        $user->setEmail($member->email());
        $user->setUsername($member->username());
        $user->setPassword($this->hasher->hashPassword($user, $member->password()));
        $user->setActive($member->isActive());

        $this->_em->persist($user);
        $this->_em->flush();
    }
}
