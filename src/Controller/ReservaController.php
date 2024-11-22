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

            $fechaActual = new \DateTime('now');

            if ($fechaInicio < $fechaActual->setTime(0, 0)) {
                $this->addFlash('error', 'La fecha de inicio no puede ser anterior a la fecha actual.');
                return $this->redirectToRoute('reservar_vehiculo', ['vehiculo_id' => $vehiculo_id]);
            }

            if ($fechaFinalizacion < $fechaInicio) {
                $this->addFlash('error', 'La fecha de finalización debe ser igual o posterior a la fecha de inicio.');
                return $this->redirectToRoute('reservar_vehiculo', ['vehiculo_id' => $vehiculo_id]);
            }

            $disponible = $reservaManager->verificarDisponibilidad($vehiculo, $fechaInicio, $fechaFinalizacion);
            if (!$disponible) {
                $this->addFlash('error', 'El vehículo ya está reservado en el rango de fechas seleccionado.');
                return $this->redirectToRoute('reservar_vehiculo', ['vehiculo_id' => $vehiculo_id]);
            }

            $cantidadPersonas = $request->request->get('cantidad_personas');

            $usuario = $this->getUser();
            $reservaManager->crearReserva($usuario, $vehiculo, $fechaInicio, $fechaFinalizacion, $cantidadPersonas);

            return $this->redirectToRoute('reserva_success');
        }

        return $this->render('reserva/reserva.html.twig', [
            'vehiculo' => $vehiculo
        ]);
    }

    #[Route('/reserva/success', name: 'reserva_success')]
    public function success(): Response
    {
        return $this->render('reserva/success.html.twig');
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
