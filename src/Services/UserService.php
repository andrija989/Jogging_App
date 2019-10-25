<?php

namespace App\Services;

use App\DataTransferObjects\ListUsersDTO;
use App\DataTransferObjects\UserDTO;
use App\Entity\User;
use App\Exceptions\UserNotFoundException;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;

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
    public function getUser($userId): User
    {
       return $this->userRepository->ofId($userId);
    }

    /**
     * @return ListUsersDTO
     */
    public function showUsers(): ListUsersDTO
    {
        $users = $this->userRepository->filter();
        return new ListUsersDTO($users);
    }

    /**
     * @param $userId
     *
     * @return User
     *
     * @throws NonUniqueResultException
     * @throws UserNotFoundException
     * @throws ORMException
     */
    public function deleteUser($userId)
    {
        $user = $this->userRepository->ofId($userId);

        $this->userRepository->remove($user);
    }

    /**
     * @param int $userId
     * @param string $role
     *
     * @return UserDTO
     *
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws UserNotFoundException
     */
    public function updateUserRole(int $userId, string $role): UserDTO
    {
        $user = $this->userRepository->ofId($userId);
        $user->setRoles($role);
        $this->userRepository->add($user);

        return new UserDTO($user);
    }
}
