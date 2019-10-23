<?php

namespace App\Exceptions;

class UserNotFoundException extends \Exception
{
    /**
     * UserNotFoundException constructor.
     */
    public function __construct()
    {
        parent::__construct('User not found');
    }
}
