<?php


namespace App\Repository\Interfaces;

use App\Entity\Record;
use App\Filters\RecordFilter;

interface RecordRepositoryInterface
{
     /**
      * @param Record $user
      * @return Record
      */
     public function add(Record $user): Record;

     /**
      * @param Record $user
      * @return mixed
      */
     public function remove(Record $user);

     /**
      * @param int $id
      * @return Record
      */
     public function ofId(int $id): ?Record;

     /**
      * @return Record[[
      */
     public function filter(RecordFilter $filter): array;
}
