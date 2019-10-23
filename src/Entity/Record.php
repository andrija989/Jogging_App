<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecordRepository")
 */
class Record
{
    /**
     * @var int $id
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var DateTime $date
     *
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @var int $distance
     *
     * @ORM\Column(type="integer")
     */
    private $distance;

    /**
     * @var int $time
     *
     * @ORM\Column(type="integer")
     */
    private $time;

    /**
     * @var User $user
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="records")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * Record constructor.
     *
     * @param DateTime $date
     * @param int $distance
     * @param int $time
     * @param User $user
     */
    public function __construct(
        DateTime $date,
        int $distance,
        int $time,
        User $user
    ) {
        $this->date = $date;
        $this->distance = $distance;
        $this->time = $time;
        $this->user = $user;
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
     * @param DateTime $date
     */
    public function setDate(DateTime $date): void
    {
        $this->date = $date;
    }

    /**
     * @return int
     */
    public function getDistance(): int
    {
        return $this->distance;
    }

    /**
     * @param int $distance
     */
    public function setDistance(int $distance): void
    {
        $this->distance = $distance;
    }

    /**
     * @return int
     */
    public function getTime(): int
    {
        return $this->time;
    }

    /**
     * @param int $time
     */
    public function setTime(int $time): void
    {
        $this->time = $time;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}
