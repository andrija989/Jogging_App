<?php

namespace App\Filters\Builders;

use App\Filters\RecordFilter;
use DateTime;

class RecordFilterBuilder
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

    public function __construct()
    {
    }

    /**
     * @return RecordFilterBuilder
     */
    public static function valueOf(): RecordFilterBuilder
    {
        return new static();
    }

    /**
     * @param  int $userId
     * @return RecordFilterBuilder
     */
    public function setUserId($userId): RecordFilterBuilder
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @param DateTime $dateFrom
     * @return RecordFilterBuilder
     */
    public function setDateFrom($dateFrom): RecordFilterBuilder
    {
        $this->dateFrom = $dateFrom;

        return $this;
    }

    /**
     * @param DateTime $dateTo
     * @return $this
     */
    public function  setDateTo($dateTo)
    {
        $this->dateTo = $dateTo;

        return $this;
    }

    /**
     * @return RecordFilter
     */
    public function build()
    {
        $recordFilter = new RecordFilter(
            $this->userId,
            $this->dateFrom,
            $this->dateTo
        );

        return $recordFilter;
    }
}
