<?php

namespace App\Service ;

use Symfony\Component\Validator\Constraints as Assert;

class ReserverSeanceRequete
{
    #[Assert\NotBlank(
        message: "L'id de la séance est obligatoire"
    )]
    public int $seanceId;
    #[Assert\NotBlank(
        message: "Le nombre de place est obligatoire"
    )]
    #[Assert\Positive(
        message: "Le nombre de place doit est positif"
    )]
    public int $nombrePlaceResa;

    /**
     * @param int $seanceId
     * @param int $nombrePlaceResa
     */
    public function __construct(int $seanceId, int $nombrePlaceResa)
    {
        $this->seanceId = $seanceId;
        $this->nombrePlaceResa = $nombrePlaceResa;
    }
}