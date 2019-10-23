<?php

namespace App\Filters\Builders;

use App\Filters\RecordFilter;
use DateTime;
use Exception;

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

    /**
     * RecordFilterBuilder constructor.
     */
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
     *
     * @return RecordFilterBuilder
     */
    public function setUserId(int $userId): RecordFilterBuilder
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @param string|null $dateFrom
     *
     * @return RecordFilterBuilder
     *
     * @throws Exception
     */
    public function setDateFrom(string $dateFrom = null): RecordFilterBuilder
    {
        if ($dateFrom) {
            $this->dateFrom = new DateTime($dateFrom);
        }

        return $this;
    }

    /**
     * @param string|null $dateTo
     *
     * @return RecordFilterBuilder
     *
     * @throws Exception
     */
    public function setDateTo(string $dateTo = null): RecordFilterBuilder
    {
        if ($dateTo) {
            $this->dateTo = new DateTime($dateTo);
        }

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
