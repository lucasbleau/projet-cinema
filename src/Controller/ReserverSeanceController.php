<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ReserverSeanceController extends AbstractController
{
    #[Route('/reserverSeance', name: 'app_reserver_seance')]
    public function reserver(TokenInterface $token): Response
    {


        $email = $token->getUser()->getUserIdentifier();



        return $this->render('reserver_seance/index.html.twig', [
            'controller_name' => 'ReserverSeanceController',
        ]);
    }
}
