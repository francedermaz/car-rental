<?php

namespace App\Entity;

use App\Repository\ReservaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservaRepository::class)]
class Reserva
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fecha_inicio = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fecha_finalizacion = null;

    #[ORM\Column]
    private ?int $cantidad_personas = null;

    #[ORM\ManyToOne(inversedBy: 'reservas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Usuario $usuario = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Vehiculo $vehiculo = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFechaInicio(): ?\DateTimeInterface
    {
        return $this->fecha_inicio;
    }

    public function setFechaInicio(\DateTimeInterface $fecha_inicio): static
    {
        $this->fecha_inicio = $fecha_inicio;

        return $this;
    }

    public function getFechaFinalizacion(): ?\DateTimeInterface
    {
        return $this->fecha_finalizacion;
    }

    public function setFechaFinalizacion(\DateTimeInterface $fecha_finalizacion): static
    {
        $this->fecha_finalizacion = $fecha_finalizacion;

        return $this;
    }

    public function getCantidadPersonas(): ?int
    {
        return $this->cantidad_personas;
    }

    public function setCantidadPersonas(int $cantidad_personas): static
    {
        $this->cantidad_personas = $cantidad_personas;

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): static
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getVehiculo(): ?Vehiculo
    {
        return $this->vehiculo;
    }

    public function setVehiculo(?Vehiculo $vehiculo): static
    {
        $this->vehiculo = $vehiculo;

        return $this;
    }
}
