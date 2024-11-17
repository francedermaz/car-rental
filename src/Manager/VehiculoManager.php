<?php

namespace App\Manager;

use App\Repository\VehiculoRepository;

class VehiculoManager
{
    private $vehiculoRepository;

    public function __construct(VehiculoRepository $vehiculoRepository)
    {
        $this->vehiculoRepository = $vehiculoRepository;
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
}
