<?php

namespace App\Repository;

use App\Entity\Record;
use App\Exceptions\RecordNotFoundException;
use App\Filters\RecordFilter;
use App\Repository\Interfaces\RecordRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;

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
     * @throws RecordNotFoundException
     * @throws NonUniqueResultException
     */
    public function ofId(int $id): Record
    {
        $query = $this->createQueryBuilder('u')
            ->where("u.id = :id")
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
        if($query) {
            return $query;
        } else {
            throw new RecordNotFoundException();
        }
    }

    /**
     * @param Record $record
     *
     * @return Record
     *
     * @throws ORMException
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
            $query
                ->andWhere('u.user = :id')
                ->setParameter('id', $filter->getUserId());
        }

        if ($filter->getDateTo()) {
            $query
                ->andWhere('u.date < :dateTo')
                ->setParameter('dateTo', $filter->getDateTo());
        }

        if ($filter->getDateFrom()) {
            $query
                ->andWhere('u.date > :dateFrom')
                ->setParameter('dateFrom', $filter->getDateFrom());
        }

        return $query
            ->getQuery()
            ->getResult();
    }
}
