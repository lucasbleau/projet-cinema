<?php

namespace App\Controller;

use App\Repository\FilmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
class FilmsController extends AbstractController
{
    #[Route('/listerFilmsAffiche', name: 'app_lister_films_affiche')]
    public function index(FilmRepository $filmRepository, SerializerInterface $serializer)
    {
        $filmsAffiche = $filmRepository->ListerFilmsAffiche();
        $filmsSerialized = $serializer->serialize($filmsAffiche, 'json', ['groups' => 'listFilmsAffiche']);
        return new Response($filmsSerialized, 200, [
            'content-type' => 'application/json'
        ]);
    }

    #[Route('/detail-film/{id}', name: 'app_detail_film')]
    public function detailFilm(FilmRepository $filmRepository, SerializerInterface $serializer, int $id)
    {
        $film = $filmRepository->detailFilm($id);
        $filmsSerialized = $serializer->serialize($film, 'json', ['groups' => 'detail']);
        return new Response($filmsSerialized, 200, [
            'content-type' => 'application/json'
        ]);
    }
}
