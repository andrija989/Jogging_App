<?php

namespace App\DataTransferObjects;

class ListRecordsDTO
{
    /**
     * @var array $records
     */
    private $records;

    /**
     * ListRecordsDTO constructor.
     *
     * @param array $records
     */
    public function __construct(array $records)
    {
        $this->records = [];
        foreach ($records as $record){
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
