<?php


 namespace App\Repository\Interfaces;

 use App\Entity\Record;

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
     public function ofId(int $id): array;

     /**
      * @return Record[[
      */
     public function filter(): array;
 }
