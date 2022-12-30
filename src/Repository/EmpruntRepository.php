<?php

namespace App\Repository;

use App\Entity\Emprunt;
use App\Entity\Emprunteur;
use App\Entity\Livre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<Emprunt>
 *
 * @method Emprunt|null find($id, $lockMode = null, $lockVersion = null)
 * @method Emprunt|null findOneBy(array $criteria, array $orderBy = null)
 * @method Emprunt[]    findAll()
 * @method Emprunt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmpruntRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Emprunt::class);
    }

    public function add(Emprunt $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Emprunt $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

       /**
    * @return Emprunt[] Returns an array of Emprunt objects
    */
   public function findByLastTen(): array
   {
       return $this->createQueryBuilder('e')

           ->orderBy('e.date_emprunt', 'DESC')
           ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
   }

      /**
    * @return Emprunt[] Returns an array of Emprunt objects
    */
   public function findByEmprunteur(Emprunteur $emprunteur): array
   {
       return $this->createQueryBuilder('e')
       ->join("e.emprunteur", "emprunteur")
           ->andWhere('emprunteur.id = :val')
           ->setParameter('val', $emprunteur->getId())
           ->orderBy('e.id', 'ASC')
           ->getQuery()
           ->getResult()
       ;
   }
         /**
    * @return Emprunt[] Returns an array of Emprunt objects
    */
    public function findByBook(Livre $livre): array
    {
        return $this->createQueryBuilder('e')
        ->join("e.livre", "livre")
            ->where('livre.id = :val')
            ->andWhere('e.date_retour IS NULL')
            ->setParameter('val', $livre->getId())
            ->orderBy('e.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
   /**
    * @return Emprunt[] Returns an array of Emprunt objects
    */
   public function findByRetour(): array
   {
       return $this->createQueryBuilder('e')
           ->andWhere('e.date_retour IS NULL')
           ->orderBy('e.id', 'ASC')
           ->getQuery()
           ->getResult()
       ;
   }

//    /**
//     * @return Emprunt[] Returns an array of Emprunt objects
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

//    public function findOneBySomeField($value): ?Emprunt
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
