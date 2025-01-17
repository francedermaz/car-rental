<?php

namespace App\Controller;

use App\Manager\VehiculoManager;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/vehiculo/{id}', name: 'detalle_vehiculo')]
    public function detalleVehiculo(VehiculoManager $vehiculoManager, string $id): Response
    {
        $vehiculo = $vehiculoManager->getVehiculo($id);

        $usuario = $this->getUser();

        if ($usuario && $this->isGranted('ROLE_ADMIN')) {
            return $this->render('vehiculos/detalle_admin.html.twig', ['vehiculo' => $vehiculo]);
        }

        return $this->render('vehiculos/detalle.html.twig', ['vehiculo' => $vehiculo]);
    }

    #[Route('/vehiculos/agregar', name: 'agregar_vehiculo', methods: ['GET', 'POST'])]
    public function agregarVehiculo(Request $request, VehiculoManager $vehiculoManager): Response
    {

        if ($request->isMethod('POST')) {
            $marca = $request->request->get('marca');
            $modelo = $request->request->get('modelo');
            $detalle = $request->request->get('detalle');
            $imagen = $request->request->get('imagen');
            $year = $request->request->get('year');
            $valor = $request->request->get('valor');

            $vehiculoManager->agregarVehiculo($marca, $modelo, $detalle, $imagen, $year, $valor);
            $this->addFlash('success', '¡El vehículo se ha añadido con éxito!');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('vehiculos/agregar_vehiculo.html.twig');
    }

    #[Route('/vehiculo/actualizar/{id}', name: 'actualizar_vehiculo', methods: ['POST'])]
    public function actualizarVehiculo(Request $request, VehiculoManager $vehiculoManager, string $id): Response
    {
        $vehiculo = $vehiculoManager->getVehiculo($id);

        $nombre = $request->request->get('nombre');
        $descripcion = $request->request->get('descripcion');
        $imagen = $request->request->get('imagen');
        $valor = $request->request->get('valor');
        $anio = $request->request->get('anio');  // Añadimos el año

        $vehiculoManager->guardarVehiculo($vehiculo, $nombre, $descripcion, $imagen, $valor, $anio);
        $this->addFlash('success', '¡El vehículo se ha modificado con éxito!');
        return $this->redirectToRoute('detalle_vehiculo', ['id' => $id]);
    }

    #[Route('/vehiculo/eliminar/{id}', name: 'eliminar_vehiculo', methods: ['POST'])]
    public function eliminarVehiculo(VehiculoManager $vehiculoManager, string $id): Response
    {
        $vehiculo = $vehiculoManager->getVehiculo($id);

        if (!$vehiculo) {
            $this->addFlash('error', 'El vehículo no existe.');
            return $this->redirectToRoute('app_home');
        }

        $reservas = $vehiculoManager->encontrarVehiculosEnReserva($vehiculo);

        if (count($reservas) > 0) {
            $this->addFlash('error', 'No se puede eliminar el vehículo porque tiene reservas asociadas.');
            return $this->redirectToRoute('detalle_vehiculo', ['id' => $id]);
        }

        $vehiculoManager->eliminarVehiculo($vehiculo);
        $this->addFlash('success', '¡El vehículo ha sido eliminado con éxito!');

        return $this->redirectToRoute('app_home');
    }
}
