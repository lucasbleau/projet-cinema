<?php

namespace App\Service;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class ConnecterUser
{
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;
    private UserPasswordEncoderInterface $passwordEncoder;
    private JWTTokenManagerInterface $jwtManager;

    public function __construct(
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder,
        JWTTokenManagerInterface $jwtManager
    ) {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->jwtManager = $jwtManager;
    }

    public function execute(ConnecterUserRequete $requete): ?string
    {
        $user = $this->userRepository->findOneBy(['email' => $requete->email]);

        // Vérifier si l'utilisateur existe et si le mot de passe est correct
        if (!$user || !$this->passwordEncoder->isPasswordValid($user, $requete->password)) {
            throw new \Exception("Email ou mot de passe incorrect.");
        }

        return $this->generateToken($user);
    }

    private function generateToken($user): string
    {
        $token = $this->jwtManager->create($user);
        return $token;
    }
}
