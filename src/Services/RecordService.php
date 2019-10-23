<?php

namespace App\Services;

use App\Entity\Record;
use App\Exceptions\RecordNotFoundException;
use App\Filters\Builders\RecordFilterBuilder;
use App\Repository\Interfaces\RecordRepositoryInterface;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Repository\RecordRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Exception;

class RecordService
{
    /**
     * @var RecordRepository
     */
    private $recordRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * RecordService constructor.
     *
     * @param UserRepositoryInterface $userRepository
     * @param RecordRepositoryInterface $recordRepository
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        RecordRepositoryInterface $recordRepository
    ) {
        $this->userRepository = $userRepository;
        $this->recordRepository = $recordRepository;
    }

    /**
     * @param int $userId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     *
     * @return Record[]|array
     *
     * @throws Exception
     */
    public function getRecords(int $userId, string $dateFrom = null, string $dateTo = null): array
    {
        $filter = RecordFilterBuilder::valueOf()
            ->setUserId($userId)
            ->setDateFrom($dateFrom)
            ->setDateTo($dateTo);

        $records = $this->recordRepository->filter($filter->build());

        return $records;
    }

    /**
     * @param int $recordId
     *
     * @return Record
     *
     * @throws NonUniqueResultException
     * @throws RecordNotFoundException
     */
    public function getRecord(int $recordId)
    {
        return $this->recordRepository->ofId($recordId);
    }

    /**
     * @param Record $record
     *
     * @throws ORMException
     */
    public function storeRecord(Record $record)
    {
        $this->recordRepository->add($record);
    }

    /**
     * @param int $id
     *
     * @return Record
     *
     * @throws RecordNotFoundException
     * @throws NonUniqueResultException
     */
    public function editRecord(int $id)
    {
        return $this->recordRepository->ofId($id);
    }

    /**
     * @param int $id
     * @param int $distance
     * @param int $time
     *
     * @return Record
     *
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws RecordNotFoundException
     */
    public function updateRecord(int $id, int $distance, int $time)
    {
        $record = $this->getRecord($id);
        $record->setDistance($distance);
        $record->setTime($time);
        $this->recordRepository->add($record);
    }

    public function deleteRecordById($recordId)
    {
        $record = $this->recordRepository->ofId($recordId);

        $this->recordRepository->remove($record);
    }
    /**
     * @param array $records
     * @param string $reportDate
     *
     * @return int
     */
    public function averageDistance(array $records,string $reportDate)
    {
        $distance = 0;
        $averageDistance = 0;
        $counter = 0.0001;

        foreach($records as $record) {
            if ( $record->getDate()->format('W') == $reportDate)
            {
                $distance += $record->getDistance();
                $counter++;
            }
            $averageDistance = $distance / $counter;
        }

        return $averageDistance;
    }

    /**
     * @param array $records
     * @param string $reportDate
     *
     * @return int
     */
    public function averageTime(array $records,string $reportDate)
    {
        $time = 0;
        $averageTime = 0;
        $counter = 0.0001;

        foreach($records as $record) {
            if ( $record->getDate()->format('W') == $reportDate)
            {
                $time += $record->getTime();
                $counter++;
            }
            $averageTime = $time / $counter;
        }

        return $averageTime;
    }

    /**
     * @param array $records
     *
     * @return array
     */
    public function getWeeks(array $records)
    {
        $weeks = [];
        foreach ($records as $record)
        {
            $weeks[] += $record->getDate()->format('W');
        }
        $weeks = array_unique($weeks);
        return $weeks;
    }
}