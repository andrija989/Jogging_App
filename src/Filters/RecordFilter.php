<?php

namespace App\Filters;

class RecordFilter
{
    /**
     * @var int $userId;
     */
    private $userId;

    /**
     * @var null $dateFrom;
     */
    private $dateFrom;

    /**
     * @var null $dateTo;
     */
    private $dateTo;


    public function __construct(int $userId = null, $dateFrom = null, $dateTo = null)
    {
        $this->userId = $userId;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function getDateFrom()
    {
        return $this->dateFrom;
    }

    public function setDateFrom($dateFrom)
    {
        $this->dateFrom = $dateFrom;
    }

    public function  getDateTo()
    {
        return $this->dateTo;
    }

    public function setDateTo($dateTo)
    {
        $this->dateTo = $dateTo;
    }
}