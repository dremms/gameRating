<?php

namespace App\Repository;

use App\Entity\UserGame;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<UserGame>
 */
class UserGameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserGame::class);
    }
    public function findByUserSorted(UserInterface $user, string $sort, string $dir, string $filterStartDate, string $filterEndDate): array
    {
        $allowedSorts = [
            'title',
            'playStartDate',
            'playEndDate',
            'playTimeSeconds',
            'completedStory',
            'completedFull',
            'earlyAccess',
            'scorePercent',
            ];
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'title';
        }
        $dir = strtolower($dir) === 'desc' ? 'DESC' : 'ASC';

        return $this->createQueryBuilder('ug')
            ->leftJoin('ug.game', 'g')
            ->andWhere('ug.user = :user')
            ->andWhere('ug.playEndDate >= :filterStartDate')
            ->andWhere('ug.playEndDate <= :filterEndDate')
            ->setParameter('user', $user)
            ->setParameter('filterStartDate', new \DateTime($filterStartDate))
            ->setParameter('filterEndDate', new \DateTime($filterEndDate))
            ->orderBy($sort === 'title' ? 'g.title' : "ug.$sort", $dir)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return UserGame[] Returns an array of UserGame objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?UserGame
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
