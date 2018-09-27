<?php
/**
 * Created by PhpStorm.
 * User: leazygomalas
 * Date: 27/09/2018
 * Time: 12:07
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class TaskRepository extends EntityRepository
{
    public function findAllTasksDone()
    {
        /**
         * @return Task[]
         */
        return $this->createQueryBuilder('task')
            ->andWhere('task.isDone = :isDone')
            ->setParameter('isDone', true)
            ->orderBy('task.createdAt', 'DESC')
            ->getQuery()
            ->execute();
    }

    public function findAllTasksTodo()
    {
        /**
         * @return Task[]
         */
        return $this->createQueryBuilder('task')
            ->andWhere('task.isDone = :isDone')
            ->setParameter('isDone', false)
            ->orderBy('task.createdAt', 'DESC')
            ->getQuery()
            ->execute();
    }
}