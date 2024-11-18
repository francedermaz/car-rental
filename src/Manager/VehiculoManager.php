<?php

namespace App\Manager;

use App\Repository\VehiculoRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Vehiculo;

class VehiculoManager
{
    private $vehiculoRepository;
    private $entityManager;

    public function __construct(VehiculoRepository $vehiculoRepository, EntityManagerInterface $entityManager)
    {
        $this->vehiculoRepository = $vehiculoRepository;
        $this->entityManager = $entityManager;
    }

    public function getVehiculos()
    {
        return $this->vehiculoRepository->findAll();
    }

    public function getVehiculo(string $id)
    {
        $vehiculo = $this->vehiculoRepository->find($id);
        return $vehiculo;
    }

    // METODO GUARDAR CAMBIOS
    public function guardarVehiculo(Vehiculo $vehiculo): void
    {
        $this->entityManager->persist($vehiculo);  
        $this->entityManager->flush();             
    }
}
