<?php

namespace App\Repository;

use App\Entity\Emprunt;
use App\Entity\Emprunteur;
use App\Entity\Livre;
use DateInterval;
use DateTime;
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
    public function findByEmprunteur(Emprunteur $emprunteur): array
    {
        return $this->createQueryBuilder('e')
            ->join('e.emprunteur', 'ee')
            ->andWhere('ee.id = :emprunteurId')
            ->setParameter('emprunteurId', $emprunteur->getId())
            ->orderBy('e.id', 'ASC')
            ->getQuery()
            ->getResult();;
    }

    /**
     * @return Emprunt[] Returns an array of Emprunt objects
     */
    public function findByLivre(Livre $livre): array
    {
        return $this->createQueryBuilder('e')
            ->join('e.livre', 'l')
            ->andWhere('l.id = :livreId')
            ->setParameter('livreId', $livre->getId())
            ->orderBy('e.id', 'ASC')
            ->getQuery()
            ->getResult();;
    }

    /**
     * @return Emprunt[] Returns an array of Emprunt objects
     */
    public function findByDateRetourBefore(DateTime $date): array
    {
        // création d'un intervalle de 1 jour
        $interval = DateInterval::createFromDateString('1 day');
        // ajout d'un jour à la date
        $date = $date->add($interval);

        return $this->createQueryBuilder('e')
            ->andWhere('e.date_retour <= :date')
            ->setParameter('date', $date->format('Y-m-d 00:00:00'))
            ->orderBy('e.date_retour', 'ASC')
            ->addOrderBy('e.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Emprunt[] Returns an array of Emprunt objects
     */
    public function findByDateRetourIsNull(): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.date_retour IS NULL')
            ->orderBy('e.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Emprunt[] Returns an array of Emprunt objects
     */
    public function findByLivreIsNull(Livre $livre): array
    {
        return $this->createQueryBuilder('e')
            ->join('e.livre', 'l')
            ->andWhere('l.id = :livreId')
            ->andWhere('e.date_retour IS NULL')
            ->setParameter('livreId', $livre->getId())
            ->orderBy('e.id', 'ASC')
            ->getQuery()
            ->getResult();;
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
