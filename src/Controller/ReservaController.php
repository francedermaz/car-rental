<?php

namespace App\Controller;

use App\Manager\ReservaManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservaController extends AbstractController
{
    #[Route('/reserva/reservar/{vehiculo_id}', name: 'reservar_vehiculo')]
    public function gestionarReserva(Request $request, ReservaManager $reservaManager, int $vehiculo_id): Response
    {
        $vehiculo = $reservaManager->obtenerVehiculoPorId($vehiculo_id);

        if ($request->isMethod('POST')) {
            $fechaInicio = new \DateTime($request->request->get('fecha_inicio'));
            $fechaFinalizacion = new \DateTime($request->request->get('fecha_finalizacion'));
            $cantidadPersonas = $request->request->get('cantidad_personas');
            $total = $vehiculo->getValor() * ($fechaFinalizacion->diff($fechaInicio)->days) * $cantidadPersonas;

            $usuario = $this->getUser();
            $reservaManager->crearReserva($usuario, $vehiculo, $fechaInicio, $fechaFinalizacion, $cantidadPersonas, $total);

            // TODO: Redirect to pagina de success, armarla
            return $this->redirectToRoute('app_home');
        }

        return $this->render('reserva/reserva.html.twig', [
            'vehiculo' => $vehiculo
        ]);
    }
}