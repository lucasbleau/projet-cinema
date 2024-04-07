<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreerUser
{
    private ValidatorInterface $validateur;
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(ValidatorInterface $validateur,UserRepository $userRepository,EntityManagerInterface $entityManager)
    {
        $this->validateur = $validateur;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @throws Exception
     */
    public function execute(CreerUserRequete $requete) :  ?User
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

        // Vérifier que l'email n'existe pas déjà
        if ($this->userRepository->findBy(['email' => $requete->email])<>null)
        {
            throw new Exception("Un compte est deja associer a cette adresse e-mail !");
        }

        // Créer le User si il y a pas d'erreur
        $user = new User();
        $user->setEmail($requete->email);
        $user->setRoles(['ROLE_USER']);

        // Hache le mot de passe avant de le rentrer dans la BDD
        $passwordHache = password_hash($requete->password, PASSWORD_BCRYPT);
        $user->setPassword($passwordHache);

        // Ajout a la BDD
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }


}