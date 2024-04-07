<?php

namespace App\Service ;

use Symfony\Component\Validator\Constraints as Assert;

class CreerUserRequete
{
    public string $nom ;
    #[Assert\NotBlank(
        message: "L'email est obligatoire"
    )]
    #[Assert\Email(
        message: "L'email {{ value }} est incorrect"
    )]
    public string $email ;

    #[Assert\NotBlank(
        message: "Le mot de passe est obligatoire"
    )]
    #[Assert\Regex(
        pattern : "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$#!%^&*?])[A-Za-z\d@$#!%^&*?]+$/",
        message: "Votre mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractere special."
    )]
    public string $password ;

    /**
     * @param string $email
     * @param string $password
     */
    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }
}