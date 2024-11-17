<?php

namespace App\Controller;

use App\Manager\VehiculoManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VehiculosController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function listarVehiculos(VehiculoManager $vehiculoManager): Response
    {
        $vehiculos = $vehiculoManager->getVehiculos();
        return $this->render('vehiculos/vehiculos.html.twig', ['vehiculos' => $vehiculos]);
    }
}
