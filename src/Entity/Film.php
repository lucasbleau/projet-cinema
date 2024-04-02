<?php

namespace App\Entity;

use App\Repository\FilmRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FilmRepository::class)]
class Film
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['listFilmsAffiche', 'detail'])]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    #[Groups(['listFilmsAffiche', 'detail'])]
    private ?string $titreFilm = null;

    #[ORM\Column]
    #[Groups(['listFilmsAffiche', 'detail'])]
    private ?int $dureeFilm = null;

    #[ORM\OneToMany(mappedBy: 'Film', targetEntity: Seance::class)]
    #[Groups(['detail'])]
    private Collection $seances;

    public function __construct()
    {
        $this->seances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitreFilm(): ?string
    {
        return $this->titreFilm;
    }

    public function setTitreFilm(string $titreFilm): static
    {
        $this->titreFilm = $titreFilm;

        return $this;
    }

    public function getDureeFilm(): ?int
    {
        return $this->dureeFilm;
    }

    public function setDureeFilm(int $dureeFilm): static
    {
        $this->dureeFilm = $dureeFilm;

        return $this;
    }

    /**
     * @return Collection<int, Seance>
     */
    public function getSeances(): Collection
    {
        return $this->seances;
    }

    public function addSeance(Seance $seance): static
    {
        if (!$this->seances->contains($seance)) {
            $this->seances->add($seance);
            $seance->addFilm($this);
        }

        return $this;
    }

    public function removeSeance(Seance $seance): static
    {
        if ($this->seances->removeElement($seance)) {
            $seance->removeFilm($this);
        }

        return $this;
    }
}
