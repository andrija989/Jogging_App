<?php

namespace App\DataTransferObjects;

use App\Entity\Record;
use App\Entity\User;
use DateTime;

class RecordDTO
{
    /**
     * @var int $id
     */
    private $id;

    /**
     * @var DateTime $date
     */
    private $date;

    /**
     * @var int $distance
     */
    private $distance;

    /**
     * @var int $time
     */
    private $time;

    /**
     * @var User $user;
     */
    private $user;

    /**
     * RecordDTO constructor.
     *
     * @param Record $record
     */
    public function __construct(Record $record)
    {
        $this->id = $record->getId();
        $this->date = $record->getDate();
        $this->distance = $record->getDistance();
        $this->time = $record->getTime();
        $this->user = $record->getUser();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @return int
     */
    public function getDistance(): int
    {
        return $this->distance;
    }

    /**
     * @return int
     */
    public function getTime(): int
    {
        return $this->time;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}