<?php

namespace App\Repository;

use App\Entity\Record;
use App\Entity\User;
use App\Filters\RecordFilter;
use App\Repository\Interfaces\RecordRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;


class RecordRepository extends ServiceEntityRepository implements RecordRepositoryInterface
{
    /**
     * RecordRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Record::class);
    }

    /**
     * @param int $id
     *
     * @return Record
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function ofId(int $id): Record
    {
        return $this->createQueryBuilder('u')
            ->where("u.id = :id")
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param Record $record
     *
     * @return Record
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function add(Record $record): Record
    {
        $this->getEntityManager()->persist($record);
        $this->getEntityManager()->flush();

        return $record;
    }

    /**
     * @param Record $record
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function remove(Record $record): void
    {
        $this->getEntityManager()->remove($record);
        $this->getEntityManager()->flush();
    }

    /**
     * @param RecordFilter $filter
     * 
     * @return array
     */
    public function filter(RecordFilter $filter): array
    {
        $query = $this->createQueryBuilder('u');

        if ($filter->getUserId()) {
            $query->andWhere('u.user = :id')->setParameter('id', $filter->getUserId());
        }

        if ($filter->getDateTo()) {
            $query->andWhere('u.date < :dateTo')->setParameter('dateTo', $filter->getDateTo());
        }

        if ($filter->getDateFrom()) {
            $query->andWhere('u.date > :dateFrom')->setParameter('dateFrom', $filter->getDateFrom());
        }

        return $query
            ->getQuery()
            ->getResult();
    }

    public function averageTime($records,$reportDate)
    {   $time = 0;
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

    public function averageDistance($records,$reportDate)
    {   $distance = 0;
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

    public function weeksNumber($records)
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
