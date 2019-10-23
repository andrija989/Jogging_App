<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class RecordNotFoundException extends \Exception
{
    public function __construct($message = null)
    {
        parent::__construct($message, Response::HTTP_NOT_FOUND);
    }
}