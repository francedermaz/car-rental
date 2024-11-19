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
            // TODO: ver la cantidad de personas, tiene relevancia?
            return $dias * $vehiculo->getValor() * $cantidadPersonas;
        }

        return null;
    }

    public function crearReserva(Usuario $usuario, Vehiculo $vehiculo, \DateTime $fechaInicio, \DateTime $fechaFinalizacion, int $cantidadPersonas, float $total): Reserva
    {
        $reserva = new Reserva();
        $reserva->setVehiculo($vehiculo);
        $reserva->setFechaInicio($fechaInicio);
        $reserva->setFechaFinalizacion($fechaFinalizacion);
        $reserva->setCantidadPersonas($cantidadPersonas);
        // TODO: sumar total a la reserva
        //$reserva->setTotal($total);
        $reserva->setUsuario($usuario);

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
}
