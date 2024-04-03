<?php

namespace App\Repository;

use App\Entity\Film;
use App\Entity\Seance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Node\Expr\Array_;

/**
 * @extends ServiceEntityRepository<Film>
 *
 * @method Film|null find($id, $lockMode = null, $lockVersion = null)
 * @method Film|null findOneBy(array $criteria, array $orderBy = null)
 * @method Film[]    findAll()
 * @method Film[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Film::class);
    }

    public function ListerFilmsAffiche() : Array
    {
        return $this->createQueryBuilder('f')
            ->innerJoin(Seance::class, 's', 'WITH', "s.Film = f.id")
            ->where('s.dateProjection > CURRENT_TIMESTAMP()')
            ->getQuery()
            ->getResult()
        ;
    }

    public function detailFilm(int $idFilm) : Array
    {
        $date = new \DateTime();
        return $this->createQueryBuilder('f')
            ->leftJoin('f.seances', 's')
            ->addSelect('s')
            ->where('f.id = :id')
            ->andWhere('s.dateProjection >= :date')
            ->setParameter('id', $idFilm)
            ->setParameter('date', $date)
            ->orderBy('s.dateProjection', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
