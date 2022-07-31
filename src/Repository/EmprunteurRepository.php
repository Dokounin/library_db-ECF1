<?php

namespace App\Repository;

use App\Entity\Emprunteur;
use App\Entity\User;
use DateInterval;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Emprunteur>
 *
 * @method Emprunteur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Emprunteur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Emprunteur[]    findAll()
 * @method Emprunteur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmprunteurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Emprunteur::class);
    }

    public function add(Emprunteur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Emprunteur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Emprunteur[] Returns an array of Emprunteur objects
     */
    public function findByUser(User $user): array
    {
        return $this->createQueryBuilder('e')
            ->join('e.user', 'u')
            ->andWhere('u.id = :userId')
            ->setParameter('userId', $user->getId())
            ->orderBy('e.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Emprunteur[] Returns an array of Emprunteur objects
     */
    public function findByKeyword($keyword): array
    {
        return $this->createQueryBuilder('e')
            ->Where('e.nom LIKE :keyword')
            ->orWhere('e.prenom LIKE :keyword')
            ->setParameter('keyword', "%{$keyword}%")
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Emprunteur[] Returns an array of Emprunteur objects
     */
    public function findByKeywordPhone($keyword): array
    {
        return $this->createQueryBuilder('e')
            ->Where('e.tel LIKE :keyword')
            ->setParameter('keyword', "%{$keyword}%")
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Emprunteur[] Returns an array of Emprunteur objects
     */
    public function findByPublishedAtBefore(DateTime $date): array
    {
        // création d'un intervalle de 1 jour
        $interval = DateInterval::createFromDateString('1 day');
        // ajout d'un jour à la date
        $date = $date->add($interval);

        return $this->createQueryBuilder('e')
            ->andWhere('e.created_at <= :date')
            ->setParameter('date', $date->format('Y-m-d 00:00:00'))
            ->orderBy('e.created_at', 'ASC')
            ->addOrderBy('e.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

         /**
        * @return Emprunteur[] Returns an array of Emprunteur objects
        */
        public function findByIsNotActif($value): array
        {
            return $this->createQueryBuilder('e')
                ->andWhere('e.actif = :val')
                ->setParameter('val', $value)
                ->getQuery()
                ->getResult()
            ;
        }

    //    /**
    //     * @return Emprunteur[] Returns an array of Emprunteur objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Emprunteur
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
