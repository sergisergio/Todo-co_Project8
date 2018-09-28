<?php
/**
 * Created by PhpStorm.
 * User: philippetraon
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

    public function findAllTasksByDateDesc()
    {
        /**
         * @return Task[]
         */
        return $this->createQueryBuilder('task')
            ->orderBy('task.createdAt', 'DESC')
            ->getQuery()
            ->execute();
    }

    public function findAllTasksByDateAsc()
    {
        /**
         * @return Task[]
         */
        return $this->createQueryBuilder('task')
            ->orderBy('task.createdAt', 'ASC')
            ->getQuery()
            ->execute();
    }

    public function findAllTasksByAuthor()
    {
        /**
         * @return Task[]
         */
        return $this->createQueryBuilder('task')
            ->orderBy('task.user', 'ASC')
            ->getQuery()
            ->execute();
    }
}