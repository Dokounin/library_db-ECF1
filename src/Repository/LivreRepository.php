<?php

namespace App\Repository;

use App\Entity\Auteur;
use App\Entity\Emprunt;
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
     * @return Livre[] Returns an array of Book objects
     */
    public function findByKeyword(string $keyword): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.titre LIKE :keyword')
            ->setParameter('keyword', "%{$keyword}%")
            ->orderBy('b.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
    /**
     * @return Livre[] Returns an array of Book objects
     */
    public function findByAuteur(Auteur $auteurId): array
    {
        return $this->createQueryBuilder('b')
            ->join('b.auteur', 'a')
            ->andWhere('a.id= :auteurId')
            ->setParameter('auteurId', $auteurId->getId())
            ->getQuery()
            ->getResult();
    }

    public function findByGenre(Genre $genre): array
    {
        return $this->createQueryBuilder('b')
            ->join("b.genres", "g")
            ->andWhere("g.id = :gId")
            ->setParameter('gId', $genre->getId())
            ->orderBy('b.id')
            ->getQuery()
            ->getResult();
    }
    /**
     * @return Livre[] Returns an array of Book objects
     */

    /**
     * @return Livre[] Returns an array of Book objects
     */
    public function findByKeywordGenre(string $value): array
    {
        return $this->createQueryBuilder('b')
            ->join("b.genres", "g")
            ->andWhere('g.nom LIKE :val')
            ->setParameter('val', "%{$value}%")
            ->orderBy('b.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
    public function findOneByEmprunt(Emprunt $emprunt): ?Livre
    {
        return $this->createQueryBuilder('b')
            ->join('b.emprunts', 'e')
            ->andWhere('e.id = :empruntId')
            ->setParameter('empruntId', $emprunt->getId())
            ->getQuery()
            ->getOneOrNullResult();
    }
    /**
     * @return Livre[] Returns an array of Book objects
     */
    public function filter(?string $mot): array
    {
        if ($mot == null) {
            return $this->createQueryBuilder('b')
                ->orderBy('b.id', 'ASC')
                ->getQuery()
                ->getResult();
        } else {
            return $this->createQueryBuilder('b')
                ->join('b.auteur', 'a')
                ->andWhere('b.titre LIKE :keyword')
                ->orWhere('a.nom LIKE :keyword')
                ->orWhere('b.code_isbn= :Isbn')
                ->setParameter('keyword', "%{$mot}%")
                ->setParameter('Isbn', $mot)
                ->orderBy('b.id', 'ASC')
                ->getQuery()
                ->getResult();
        }
    }


    //    /**
    //     * @return Livre[] Returns an array of Book objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Book
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
