<?php

namespace App\Filters;

use DateTime;

class RecordFilter
{
    /**
     * @var int $userId;
     */
    private $userId;

    /**
     * @var DateTime $dateFrom;
     */
    private $dateFrom;

    /**
     * @var DateTime $dateTo;
     */
    private $dateTo;

    /**
     * RecordFilter constructor.
     * @param int|null $userId
     * @param DateTime $dateFrom
     * @param DateTime $dateTo
     */
    public function __construct(int $userId, $dateFrom = null, $dateTo = null)
    {
        $this->userId = $userId;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    /**
     * @return int|null;
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param $userId;
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return null;
     */
    public function getDateFrom()
    {
        return $this->dateFrom;
    }

    /**
     * @param DateTime $dateFrom;
     */
    public function setDateFrom($dateFrom)
    {
        $this->dateFrom = $dateFrom;
    }

    /**
     * @return null;
     */
    public function getDateTo()
    {
        return $this->dateTo;
    }

    /**
     * @param $dateTo;
     */
    public function setDateTo($dateTo)
    {
        $this->dateTo = $dateTo;
    }
}
