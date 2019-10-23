<?php

namespace App\Entity;

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
     * @var \DateTimeImmutable $date
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
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @param \DateTimeInterface $date
     */
    public function setDate(\DateTimeInterface $date): void
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

    /**
     * @param User $user
     */
    public function setUserId(?User $user): void
    {
        $this->user = $user;
    }
}
