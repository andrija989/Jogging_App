<?php

namespace App\DataTransferObjects;

class ListUsersDTO
{
    /**
     * @var array $users
     */
    private $users;

    /**
     * ListUsersDTO constructor.
     *
     * @param array $users
     */
    public function __construct(array $users)
    {
        $this->users = [];
        foreach ($users as $user){
            $this->users[] = new UserDTO($user);
        }
    }

    /**
     * @return array
     */
    public function getUsers(): array
    {
        return $this->users;
    }
}
