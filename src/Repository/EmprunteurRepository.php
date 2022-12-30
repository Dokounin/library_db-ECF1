<?php

namespace App\Repository;

use App\Entity\Emprunteur;
use App\Entity\User;
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
    public function findByKeyword(string $keyword): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.nom LIKE :val')
            ->orWhere('e.prenom LIKE :val')
            ->setParameter('val', "%{$keyword}%")
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Emprunteur[] Returns an array of Emprunteur objects
     */
    public function findByTel(string $telNum): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.tel LIKE :val')
            ->setParameter('val', "%{$telNum}%")
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Emprunteur[] Returns an array of Emprunteur objects
     */
    public function findByCreatedAt(DateTime $createdAt): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.created_at < :createdAt')
            ->setParameter('createdAt', $createdAt)
            ->orderBy('e.id', 'ASC')

            ->getQuery()
            ->getResult();
    }
    /**
     * @return Emprunteur[] Returns an array of Emprunteur objects
     */
    public function isActif(): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.actif = 1')

            ->orderBy('e.id', 'ASC')

            ->getQuery()
            ->getResult();
    }

    public function findByUser(User $user): ?Emprunteur
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.user = :val')
            ->setParameter('val', $user->getId())
            ->getQuery()
            ->getOneOrNullResult();
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
