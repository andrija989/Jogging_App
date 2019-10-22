<?php


namespace App\Repository\Interfaces;

use App\Entity\Record;
use App\Filters\RecordFilter;

interface RecordRepositoryInterface
{
     /**
      * @param Record $user
      *
      * @return Record
      */
     public function add(Record $user): Record;

     /**
      * @param Record $user
      */
     public function remove(Record $user): void;

     /**
      * @param int $id
      *
      * @return Record
      *
      * @throws RecordNotFoundException
      */
     public function ofId(int $id): Record;

    /**
     * @param RecordFilter $filter
     *
     * @return Record[]
     */
     public function filter(RecordFilter $filter): array;
}
