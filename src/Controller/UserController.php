<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\ConnecterUser;
use App\Service\CreerUser;
use App\Service\CreerUserRequete;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

#[Route('/api')]
class UserController extends AbstractController
{
    #[Route('/inscription', name: 'app_inscription')]
    public function inscription(\Symfony\Component\HttpFoundation\Request $request,ValidatorInterface $validateur,UserRepository $userRepository,EntityManagerInterface $entityManager,SerializerInterface $serializer): Response|JsonResponse
    {
        // récupere les données de la requete sous form de tableau
        $donnees = json_decode($request->getContent(), true);
        // Création des classes
        $requete = new CreerUserRequete($donnees["email"],$donnees["password"]);
        $creerUser = new CreerUser($validateur,$userRepository,$entityManager);

        try
        {
            // Créer le user
            $user = $creerUser->execute($requete);
            // Si pas d'erreur on renvoie le User avec un status 201
            $userSerialized = $serializer->serialize($user, 'json', ['groups' => 'info_user']);
            return new Response($userSerialized, 201, [
                'content-type' => 'application/json'
            ]);
        }
        catch (\Exception $e)
        {
            // Si erreur on renvoie status 400 avec l'erreur
            return new JsonResponse($e->getMessage(), 400);
        }
    }

    #[Route('/connexion', name: 'app_connexion')]
    public function connexion(\Symfony\Component\HttpFoundation\Request $request, UserRepository $userRepository, JWTTokenManagerInterface $jwtManager): Response {

        $donnees = json_decode($request->getContent(), true);

        $email = $donnees['email'];
        $password = $donnees['password'];

        $user = $userRepository->findOneBy(['email' => $email]);

        // Vérifier si l'utilisateur existe et si le mot de passe est correct
        if (!$user || !password_verify($password, $user->getPassword())) {
            return new JsonResponse(['message' => 'Email ou mot de passe incorrect.'], Response::HTTP_UNAUTHORIZED);
        }

        // Générer le token JWT
        $token = $jwtManager->create($user);

        // Retourner une réponse avec le token
        return new JsonResponse(['token' => $token], Response::HTTP_OK);
    }

}
