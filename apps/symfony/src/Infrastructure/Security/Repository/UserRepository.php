<?php

declare(strict_types=1);

namespace App\Infrastructure\Security\Repository;

use App\Infrastructure\Security\Contracts\Repository\MembersRepositoryInterface;
use App\Infrastructure\Security\Entity\User;
use App\Infrastructure\Security\Factory\UserFactory;
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
    public function __construct(ManagerRegistry $registry, private UserPasswordHasherInterface $hasher, private UserFactory $userFactory)
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
        /** @var User $user */
        $user =  $this->createQueryBuilder('m')
                      ->where('m.email = :email')
                      ->setParameter('email', $email)
                      ->getQuery()
                      ->getOneOrNullResult() ?? throw new UserNotFoundException();

        return $user;
    }

    /**
     * @throws NonUniqueResultException
     * @throws UserNotFoundException
     */
    public function getMemberByEmail(string $email): Member
    {
        $user = $this->getUserByEmail($email);

        return $this->userFactory->createMember($user);
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
        $member->changePassword($this->hasher->hashPassword($user, $member->password()));

        $user = $this->userFactory->createFromMember($member);

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
        /** @var User $user */
        $user = $this->findOneBy(['username' => $member->username()]);
        $user = $this->userFactory->updateToMember($user, $member);

        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * @throws ORMException
     */
    public function updatePassword(Member $member, string $newPlainPassword): void
    {
        /** @var PasswordAuthenticatedUserInterface $user */
        $user = $this->findOneBy(['username' => $member->username()]);
        $this->upgradePassword($user, $newPlainPassword);
    }
}
