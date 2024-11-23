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
        return $this->vehiculoRepository->find($id);
    }

    public function guardarVehiculo(Vehiculo $vehiculo, ?string $nombre, ?string $descripcion, ?string $imagen, ?string $valor, ?string $anio): void
    {
        if ($nombre) {
            // verificar que el nombre tiene dos palabras (marca y modelo)
            $nombreArray = explode(' ', $nombre);
            if (count($nombreArray) >= 2) {
                $vehiculo->setMarca($nombreArray[0]);
                $vehiculo->setModelo($nombreArray[1]);
            }
        }
        if ($descripcion) {
            $vehiculo->setDetalle($descripcion);
        }
        if ($imagen) {
            $vehiculo->setImagen($imagen);
        }
        if ($valor) {
            $vehiculo->setValor((float) $valor);
        }
        if ($anio) {
            $vehiculo->setAnio((int) $anio);  // Guardamos el año
        }

        $this->entityManager->persist($vehiculo);
        $this->entityManager->flush();
    }

    public function eliminarVehiculo(Vehiculo $vehiculo): void
    {
        $this->entityManager->remove($vehiculo);
        $this->entityManager->flush();
    }

    public function agregarVehiculo(
        string $marca,
        string $modelo,
        ?string $detalle,
        ?string $imagen,
        int $year,
        float $valor
    ) {
        $vehiculo = new Vehiculo();
        $vehiculo->setMarca($marca)
            ->setModelo($modelo)
            ->setDetalle($detalle ?? '')
            ->setImagen($imagen ?? '')
            ->setYear($year)
            ->setValor($valor);

        $this->entityManager->persist($vehiculo);
        $this->entityManager->flush();
    }
}
