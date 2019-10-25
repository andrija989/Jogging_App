<?php

namespace App\DataTransferObjects;

use App\Entity\User;

class ListUsersDTO
{
    /**
     * @var UserDTO[] $users
     */
    private $users;

    /**
     * ListUsersDTO constructor.
     *
     * @param User[] $users
     */
    public function __construct(array $users)
    {
        $this->users = [];
        foreach ($users as $user) {
            $this->users[] = new UserDTO($user);
        }
    }

    /**
     * @return UserDTO[]
     */
    public function getUsers(): array
    {
        return $this->users;
    }
}
