<?php

namespace App\Repository;

use App\Entity\Record;
use App\Entity\User;
use App\Filters\RecordFilter;
use App\Repository\Interfaces\RecordRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Record|null find($id, $lockMode = null, $lockVersion = null)
 * @method Record|null findOneBy(array $criteria, array $orderBy = null)
 * @method Record[]    findAll()
 * @method Record[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecordRepository extends ServiceEntityRepository implements RecordRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Record::class);
    }

    public function ofId(int $id): ?Record
    {
        return $this->createQueryBuilder('u')
            ->where("u.id = :id")
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function add(Record $record): Record
    {
        $this->getEntityManager()->persist($record);
        $this->getEntityManager()->flush();

        return $record;
    }

    public function remove(Record $record)
    {
        $this->getEntityManager()->remove($record);
        $this->getEntityManager()->flush();
    }

    public function filter(RecordFilter $filter): array
    {
        $query = $this->createQueryBuilder('u');


        if ($filter->getDateFrom() == null && $filter->getDateTo() == null)
        {
            $query = $this->createQueryBuilder('u');
        }

        if($filter->getDateFrom() !== null)
        {
            $query -> Where('u.date > :dateFrom')->setParameter('dateFrom', $filter->getDateFrom());
        }

        if($filter->getDateTo() !== null)
        {
            $query -> Where('u.date < :dateTo')->setParameter('dateTo', $filter->getDateTo());
        }

        if($filter->getDateFrom() !== null && $filter->getDateTo() !== null)
        {
            $query -> Where('u.date < :dateTo')->setParameter('dateTo', $filter->getDateTo())->andWhere('u.date > :dateFrom')->setParameter('dateFrom', $filter->getDateFrom());
        }

        if($filter->getUserId())
        {
            $query -> andWhere('u.user = :id')->setParameter('id', $filter->getUserId());
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
