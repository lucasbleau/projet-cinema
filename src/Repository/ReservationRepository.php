<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 *
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    // requete qui recupere le nombre de place dispo

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
//    public function recupNbPlacesDispo(int $idSeance): array
//    {
//        $date = new \DateTime();
//        return $this->createQueryBuilder('s')
//            ->select('SUM(r.nombrePlaceResa) AS places_reservees')
//            ->leftJoin('s.reservations', 'r')
//            ->where('s.id = :idSeance')
//            ->andWhere('s.dateProjection >= :date')
//            ->setParameter('idSeance', $idSeance)
//            ->setParameter('date', $date)
//            ->getQuery()
//            ->getSingleResult();
//    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function findNbPlaceReserverByIdSeance(int $idSeance): int|null
    {
        return $this->createQueryBuilder('r')
            ->select('SUM(r.nombrePlaceResa) as nbPlace')
            ->leftJoin('r.Seance', 's')
            ->andWhere('r.Seance = :idSeance')
            ->setParameter('idSeance', $idSeance)
            ->getQuery()
            ->getSingleScalarResult();

    }
}
