<?php

namespace App\DataTransferObjects;

use App\Entity\Record;

class ListRecordsDTO
{
    /**
     * @var RecordDTO[] $records
     */
    private $records;

    /**
     * ListRecordsDTO constructor.
     *
     * @param Record[] $records
     */
    public function __construct(array $records)
    {
        $this->records = [];
        foreach ($records as $record) {
            $this->records[] = new RecordDTO($record);
        }
    }

    /**
     * @return RecordDTO[]
     */
    public function getRecords(): array
    {
        return $this->records;
    }
}
