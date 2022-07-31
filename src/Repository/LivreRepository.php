<?php

namespace App\Repository;

use App\Entity\Auteur;
use App\Entity\Genre;
use App\Entity\Livre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Livre>
 *
 * @method Livre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livre[]    findAll()
 * @method Livre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }

    public function add(Livre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Livre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Livre[] Returns an array of Livre objects
     */
    public function findByKeyword($keyword): array
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.titre LIKE :keyword')
            ->setParameter('keyword', "%{$keyword}%")
            ->orderBy('l.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Livre[] Returns an array of Livre objects
     */
    public function findByAuteur(Auteur $auteur)
    {
        return $this->createQueryBuilder('l')
            // faire une jointure avec l'utilisateur associé au profil auteur
            ->join('l.auteur', 'a')
            // ne retenir que le profil auteur qui est associé a l'utilisateur passé en paramètre de la fonction
            ->andWhere('a.id = :auteurId')
            ->setParameter('auteurId', $auteur->getId())
            // exécution de la requête
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Livre[] Returns an array of Livre objects
     */
    public function findByGenreKeyword($keyword)
    {
        return $this->createQueryBuilder('l')
            ->join('l.genres', 'g')
            ->andWhere('g.nom LIKE :keyword')
            ->setParameter('keyword', "%{$keyword}%")
            ->orderBy('l.titre', 'ASC')
            ->getQuery()
            ->getResult();
    }




    //    /**
    //     * @return Livre[] Returns an array of Livre objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Livre
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
