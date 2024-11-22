<?php

namespace App\Manager;

use App\Entity\Reserva;
use App\Entity\Usuario;
use App\Entity\Vehiculo;
use App\Repository\ReservaRepository;
use App\Repository\VehiculoRepository;
use Doctrine\ORM\EntityManagerInterface;

class ReservaManager
{
    private $vehiculoRepository, $reservaRepository;
    private $entityManager;

    public function __construct(VehiculoRepository $vehiculoRepository, ReservaRepository $reservaRepository, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->vehiculoRepository = $vehiculoRepository;
        $this->reservaRepository = $reservaRepository;
    }

    public function calcularTotalReserva(Vehiculo $vehiculo, \DateTime $fechaInicio, \DateTime $fechaFinalizacion, int $cantidadPersonas)
    {
        $dias = $fechaInicio->diff($fechaFinalizacion)->days;

        if ($dias > 0) {
            return $dias * $vehiculo->getValor();
        }

        return null;
    }

    public function crearReserva(Usuario $usuario, Vehiculo $vehiculo, \DateTime $fechaInicio, \DateTime $fechaFinalizacion, int $cantidadPersonas): Reserva
    {
        $reserva = new Reserva();
        $reserva->setVehiculo($vehiculo);
        $reserva->setFechaInicio($fechaInicio);
        $reserva->setFechaFinalizacion($fechaFinalizacion);
        $reserva->setCantidadPersonas($cantidadPersonas);
        $reserva->setUsuario($usuario);

        $total = $vehiculo->getValor() * ($fechaFinalizacion->diff($fechaInicio)->days);
        $reserva->setTotal($total);

        $this->entityManager->persist($reserva);
        $this->entityManager->flush();
        return $reserva;
    }

    public function obtenerVehiculoPorId(int $id)
    {
        return $this->vehiculoRepository->find($id);
    }

    public function getReservas(Usuario $usuario)
    {
        return $this->reservaRepository->findBy(['usuario' => $usuario]);
    }

    public function obtenerTodasLasOrdenes()
    {
        return $this->reservaRepository->findAll();
    }

    public function verificarDisponibilidad(Vehiculo $vehiculo, \DateTime $fechaInicio, \DateTime $fechaFinalizacion): bool
    {
        $reservas = $this->reservaRepository->findBy(['vehiculo' => $vehiculo]);

        foreach ($reservas as $reserva) {
            if (
                ($fechaInicio <= $reserva->getFechaFinalizacion() && $fechaFinalizacion >= $reserva->getFechaInicio())
            ) {
                return false;
            }
        }

        return true;
    }
}
