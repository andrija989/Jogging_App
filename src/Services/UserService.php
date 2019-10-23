<?php

namespace App\Services;

use App\Entity\User;
use App\Exceptions\UserNotFoundException;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;

class UserService
{
    /**
     * @var UserRepository $userRepository
     */
    private $userRepository;

    /**
     * UserService constructor.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param $userId
     *
     * @return User
     *
     * @throws UserNotFoundException
     * @throws NonUniqueResultException
     */
    public function getUser($userId)
    {
        return $this->userRepository->ofId($userId);
    }

    /**
     * @return User[]
     */
    public function showUsers()
    {
        return $this->userRepository->filter();
    }

    public function deleteUser($userId)
    {
        $user = $this->userRepository->ofId($userId);

        $this->userRepository->remove($user);
    }

    public function updateUserRole($userId, $role)
    {
        $user = $this->userRepository->ofId($userId);
        $user->setRoles($role);
        $this->userRepository->add($user);
    }
}
