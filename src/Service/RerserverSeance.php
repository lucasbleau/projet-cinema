<?php

namespace App\Service;

use App\Entity\Reservation;
use App\Entity\User;
use App\Repository\ReservationRepository;
use App\Repository\SeanceRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use http\Env\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use function PHPUnit\Framework\throwException;

class RerserverSeance
{
    private ValidatorInterface $validateur;
    private UserRepository $userRepository;
    private SeanceRepository $seanceRepository
    private EntityManagerInterface $entityManager;

    public function __construct(ValidatorInterface $validateur,UserRepository $userRepository,
                                SeanceRepository $seanceRepository, ReservationRepository $reservationRepository,
                                EntityManagerInterface $entityManager)
    {
        $this->validateur = $validateur;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->seanceRepository = $seanceRepository;
    }

    /**
     * @throws Exception
     */
    public function execute(ReserverSeanceRequete $requete, string $emailUser) :  ?Reservation
    {
        // Valider les données en entrées (de la requête)
        $erreur = $this->validateur->validate($requete);
        $messageErreur = "";

        // Ajoute toutes les erreurs dans un seul message
        if ($erreur->count()<> 0)
        {
            $messageErreur = ($erreur->get(0))->getMessage();

            for ($i=1;$i<$erreur->count();$i++)
            {
                $messageErreur .= " et ".($erreur->get($i))->getMessage();
            }
            throw new Exception($messageErreur);
        }

        // Verifier user existe
        $user = $this->userRepository->findOneBy(["email" => $emailUser]);
        if ($user == null)
        {
            throw new Exception("Utilisateur inconnu !");
        }

        // verifier user role_user
        $roles = $user->getRoles();
        $roleUser = false;

        foreach ($roles as $role)
        {
            if ($role == "ROLE_USER")
            {
                $roleUser = true;
            }
        }

        if ($roleUser == false)
        {
            throw new Exception("L'utilisateur n'a pas le role user");
        }


        // seance existe
        $seanceId = $requete->seanceId;
        $seanceExist = $this->seanceRepository->findOneBy(["id" => $seanceId]);
        if ($seanceId !== $seanceExist)
        {
            throw new Exception("La séance n'existe pas !");
        }

        // seance pas dans le passé
        $dateAujd = new \DateTime();
        if ($seanceExist->getDateProjection() < $dateAujd)
        {
            throw new Exception("La séance est déja passé !");
        }

        // nombre place resa <= nombre place dispo



        $reservation = new Reservation();
        $reservation->setSeance($seanceExist);
        $reservation->setDateResa(new \DateTime());
        $reservation->setUsers($user);
        $reservation->setNombrePlaceResa($requete->nombrePlaceResa);
        $reservation->setMontantTotal($requete->nombrePlaceResa * $seanceExist->getTarifNormal());

        // Ajout a la BDD
        $this->entityManager->persist($reservation);
        $this->entityManager->flush();

        return $reservation;
    }
}