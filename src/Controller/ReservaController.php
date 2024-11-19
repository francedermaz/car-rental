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

            return $this->render('reserva/confirmacion_reserva.html.twig');
        }

        return $this->render('reserva/reserva.html.twig', [
            'vehiculo' => $vehiculo
        ]);
    }

    #[Route('/reservas', name: 'reservas_usuario')]
    public function obtenerReservasUsuario(ReservaManager $reservaManager): Response
    {
        $usuario = $this->getUser();

        $reservas = $reservaManager->getReservas($usuario);

        return $this->render('reserva/lista_reservas.html.twig', [
            'reservas' => $reservas,
        ]);
    }

    #[Route('/ordenes', name: 'ordenes_admin')]
    public function obtenerOrdenesAdmin(ReservaManager $reservaManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'No tienes permiso para acceder a esta página.');

        $reservas = $reservaManager->obtenerTodasLasOrdenes();

        return $this->render('reserva/ordenes.html.twig', [
            'reservas' => $reservas,
        ]);
    }
}
