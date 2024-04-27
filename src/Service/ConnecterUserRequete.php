<?php

namespace App\Service;

use Symfony\Component\Validator\Constraints as Assert;

class ConnecterUserRequete
{
    #[Assert\NotBlank(
        message: "L'email est obligatoire"
    )]
    #[Assert\Email(
        message: "L'email {{ value }} est incorrect"
    )]
    public string $email;

    #[Assert\NotBlank(
        message: "Le mot de passe est obligatoire"
    )]
    public string $password;

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
