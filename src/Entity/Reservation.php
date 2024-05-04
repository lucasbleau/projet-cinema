<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['reservation'])]
    private ?int $nombrePlaceResa = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['reservation'])]
    private ?\DateTimeInterface $dateResa = null;

    #[ORM\Column]
    #[Groups(['reservation'])]
    private ?float $montantTotal = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[Groups(['reservation'])]
    private ?Seance $Seance;

    #[ORM\ManyToOne(inversedBy: 'Reservation')]
    #[Groups(['reservation'])]
    private ?User $users;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int|null
     */
    public function getNombrePlaceResa(): ?int
    {
        return $this->nombrePlaceResa;
    }

    /**
     * @param int|null $nombrePlaceResa
     */
    public function setNombrePlaceResa(?int $nombrePlaceResa): void
    {
        $this->nombrePlaceResa = $nombrePlaceResa;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDateResa(): ?\DateTimeInterface
    {
        return $this->dateResa;
    }

    /**
     * @param \DateTimeInterface|null $dateResa
     */
    public function setDateResa(?\DateTimeInterface $dateResa): void
    {
        $this->dateResa = $dateResa;
    }

    /**
     * @return float|null
     */
    public function getMontantTotal(): ?float
    {
        return $this->montantTotal;
    }

    /**
     * @param float|null $montantTotal
     */
    public function setMontantTotal(?float $montantTotal): void
    {
        $this->montantTotal = $montantTotal;
    }

    /**
     * @return Seance|null
     */
    public function getSeance(): ?Seance
    {
        return $this->Seance;
    }

    /**
     * @param Seance|null $Seance
     */
    public function setSeance(?Seance $Seance): void
    {
        $this->Seance = $Seance;
    }

    /**
     * @return User|null
     */
    public function getUsers(): ?User
    {
        return $this->users;
    }

    /**
     * @param User|null $users
     */
    public function setUsers(?User $users): void
    {
        $this->users = $users;
    }

}
