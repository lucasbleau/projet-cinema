<?php

namespace App\Entity;

use App\Repository\SeanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: SeanceRepository::class)]
class Seance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['detail', 'seance'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['detail', 'seance'])]
    private ?\DateTimeInterface $dateProjection = null;

    #[ORM\Column]
    #[Groups(['detail', 'seance'])]
    private ?float $tarifNormal = null;

    #[ORM\Column]
    #[Groups(['detail', 'seance'])]
    private ?float $tarifReduit = null;

    #[ORM\OneToMany(mappedBy: 'Seance', targetEntity: Reservation::class)]
    private Collection $reservations;

    #[ORM\ManyToOne(targetEntity: Salle::class, inversedBy: 'seances')]
    #[Groups(['seance'])]
    private Salle $Salle;

    #[ORM\ManyToOne(targetEntity: Film::class, inversedBy: 'seances')]
    private Film $Film;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateProjection(): ?\DateTimeInterface
    {
        return $this->dateProjection;
    }

    public function setDateProjection(\DateTimeInterface $dateProjection): static
    {
        $this->dateProjection = $dateProjection;

        return $this;
    }

    public function getTarifNormal(): ?float
    {
        return $this->tarifNormal;
    }

    public function setTarifNormal(float $tarifNormal): static
    {
        $this->tarifNormal = $tarifNormal;

        return $this;
    }

    public function getTarifReduit(): ?float
    {
        return $this->tarifReduit;
    }

    public function setTarifReduit(float $tarifReduit): static
    {
        $this->tarifReduit = $tarifReduit;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->addSeance($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            $reservation->removeSeance($this);
        }

        return $this;
    }

    /**
     * @param Salle $Salle
     */
    public function setSalle(Salle $Salle): void
    {
        $this->Salle = $Salle;
    }

    /**
     * @param Film $Film
     */
    public function setFilm(Film $Film): void
    {
        $this->Film = $Film;
    }

    /**
     * @return Salle
     */
    public function getSalle(): Salle
    {
        return $this->Salle;
    }

    /**
     * @return Film
     */
    public function getFilm(): Film
    {
        return $this->Film;
    }
}
