<?php

namespace App\Repository\Interfaces;

use App\Entity\User;

interface UserRepositoryInterface
{
    /**
     * @param User $user
     *
     * @return User
     */
    public function add(User $user): User;

    /**
     * @param User $user
     */
    public function remove(User $user): void;

    /**
     * @param int $id
     *
     * @return User
     */
    public function ofId(int $id): User;

    /**
     * @return User[]
     */
    public function filter(): array;
}
