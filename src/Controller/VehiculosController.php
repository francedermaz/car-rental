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

        if ($nombre) {
            // verificar q el nombre tiene dos palabras (marca y modelo)
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

        $vehiculoManager->guardarVehiculo($vehiculo);

        return $this->redirectToRoute('detalle_vehiculo', ['id' => $id]);
    }

    #[Route('/vehiculo/eliminar/{id}', name: 'eliminar_vehiculo', methods: ['POST'])]
    public function eliminarVehiculo(VehiculoManager $vehiculoManager, string $id): Response
    {
        $vehiculo = $vehiculoManager->getVehiculo($id);
        if ($vehiculo) {
            $vehiculoManager->eliminarVehiculo($vehiculo);
            $this->addFlash('success', '¡El vehículo ha sido eliminado con éxito!');
        } else {
            $this->addFlash('error', 'El vehículo no existe.');
        }

        return $this->redirectToRoute('app_home');
    }
}
