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
     *
     * @param int $userId
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
     * @return int;
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return DateTime;
     */
    public function getDateFrom()
    {
        return $this->dateFrom;
    }

    /**
     * @return DateTime;
     */
    public function getDateTo()
    {
        return $this->dateTo;
    }
}
