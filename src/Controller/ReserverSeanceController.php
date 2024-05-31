<?php

namespace App\Controller;

use App\Repository\FilmRepository;
use App\Repository\SalleRepository;
use App\Repository\SeanceRepository;
use App\Service\ReserverSeance;
use App\Service\ReserverSeanceRequete;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ReserverSeanceController extends AbstractController
{
    #[Route('api/reservation', name: 'app_reservation')]
    public function reserver(TokenInterface $token, ReserverSeance $reserverSeance,
                             \Symfony\Component\HttpFoundation\Request $request,
                             SerializerInterface $serializer): Response
    {
        $email = $token->getUser()->getUserIdentifier();
        $donnees = json_decode($request->getContent(), true);

        $requete = new ReserverSeanceRequete($donnees['idSeance'],$donnees['nbPlace']);

        try
        {
            $reservation = $reserverSeance->execute($requete, $email);

            $reservationSerialized = $serializer->serialize($reservation, 'json', ['groups' => 'info_reservation']);
            return new Response($reservationSerialized, 201, [
                'content-type' => 'application/json'
            ]);
        }
        catch (\Exception $e)
        {
            return new JsonResponse($e->getMessage(), 400);
        }
    }

    #[Route('api/info-seance', name: 'app_info_seance')]
    public function infoSeance(Request $request, ReserverSeance $reserverSeance, SerializerInterface $serializer, TokenInterface $tokenInterface, SeanceRepository $seanceRepository,FilmRepository $filmRepository, SalleRepository $salleRepository): Response
    {
        $donnees = json_decode($request->getContent(), true);
        $idSeance = $donnees["idSeance"];
        if ($idSeance == null) {
            // Si erreur on renvoie status 400 avec l'erreur
            return new JsonResponse("L'ID de la seance n'a pas ete trouve", 400);
        }

        $seance = $seanceRepository->findOneBy(["id" => $idSeance]);
        if ($seance == null) {
            // Si erreur on renvoie status 400 avec l'erreur
            return new JsonResponse("La seance n'a pas ete trouve", 400);
        }

        // Si pas d'erreur on renvoie le User avec un status 201
        $seanceSerialized = $serializer->serialize($seance, 'json', ['groups' => 'info_seance']);
        return new Response($seanceSerialized, 201, [
            'content-type' => 'application/json'
        ]);

    }
}
