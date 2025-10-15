<?php

namespace App\Entity;

use App\Repository\MeasurementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MeasurementRepository::class)]
class Measurement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'measurements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Location $location = null;

    /**
     * Przechowujemy surowy timestamp w milisekundach.
     * Kolumna w DB nazywa się nadal `date`.
     */
    #[ORM\Column(type: Types::BIGINT, name: 'date')]
    private int $dateMs = 0;

    #[ORM\Column(type: Types::DECIMAL, precision: 3, scale: 0)]
    private ?string $celsius = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): static
    {
        $this->location = $location;
        return $this;
    }

    /**
     * Wygodny getter zwracający DateTime z pola w ms.
     * Zwracamy DateTimeImmutable w lokalnej strefie (bo @timestamp jest w UTC).
     */
    public function getDate(): \DateTimeImmutable
    {
        $dt = new \DateTimeImmutable('@' . intdiv($this->dateMs, 1000));
        return $dt->setTimezone(new \DateTimeZone(date_default_timezone_get()));
    }

    /**
     * Setter przyjmujący DateTime i zapisujący ms w kolumnie BIGINT.
     */
    public function setDate(\DateTimeInterface $date): static
    {
        $this->dateMs = $date->getTimestamp() * 1000;
        return $this;
    }

    /**
     * Dostęp do surowej wartości w ms (opcjonalnie, czasem przydatne).
     */
    public function getDateMs(): int
    {
        return $this->dateMs;
    }

    public function setDateMs(int $dateMs): static
    {
        $this->dateMs = $dateMs;
        return $this;
    }

    public function getCelsius(): ?string
    {
        return $this->celsius;
    }

    public function setCelsius(string $celsius): static
    {
        $this->celsius = $celsius;
        return $this;
    }
}
