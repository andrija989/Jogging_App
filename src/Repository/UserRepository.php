<?php

namespace App\Repository;

use App\Entity\User;
use App\Exceptions\UserNotFoundException;
use App\Repository\Interfaces\UserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;

class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    /**
     * UserRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param int $id
     *
     * @return User
     *
     * @throws NonUniqueResultException
     * @throws UserNotFoundException
     */
    public function ofId(int $id): User
    {
        $user = $this->createQueryBuilder('u')
            ->where("u.id = :id")
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$user) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    /**
     * @param User $user
     *
     * @return User
     *
     * @throws ORMException
     */
    public function add(User $user): User
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        return $user;
    }

    /**
     * @param User $user
     *
     * @throws ORMException
     */
    public function remove(User $user): void
    {
        $this->getEntityManager()->remove($user);
        $this->getEntityManager()->flush();
    }

    /**
     * @return User[]
     */
    public function filter(): array
    {
        return $this->createQueryBuilder('u')
            ->getQuery()
            ->getResult();
    }
}
