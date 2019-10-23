<?php

namespace App\Exceptions;

class RecordNotFoundException extends \Exception
{
    /**
     * RecordNotFoundException constructor.
     */
    public function __construct()
    {
        parent::__construct('Record not found');
    }
}
