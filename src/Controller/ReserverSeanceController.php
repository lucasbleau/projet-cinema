<?php

namespace App\Controller;

use App\Service\ReserverSeance;
use App\Service\ReserverSeanceRequete;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ReserverSeanceController extends AbstractController
{
    #[Route('api/reserverSeance', name: 'app_reserver_seance')]
    public function reserver(TokenInterface $token, ReserverSeance $reserverSeance,
                             \Symfony\Component\HttpFoundation\Request $request, SerializerInterface $serializer): Response
    {

        $email = $token->getUser()->getUserIdentifier();
        $donnees = json_decode($request->getContent(), true);

        $requete = new ReserverSeanceRequete($donnees['seance_id'],$donnees['nombre_place_resa']);

        try
        {
            $reservation = $reserverSeance->execute($requete, $email);

            $reservationSerialized = $serializer->serialize($reservation, 'json', ['groups' => 'reservation']);
            return new Response($reservationSerialized, 201, [
                'content-type' => 'application/json'
            ]);
        }
        catch (\Exception $e)
        {
            return new JsonResponse($e->getMessage(), 400);
        }
    }
}
