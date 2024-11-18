<?php

namespace App\Controller;

use App\Manager\UsuarioManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsuarioController extends AbstractController
{
    #[Route('/usuario', name: 'ver_datos_usuario')]
    public function verDatosUsuario(): Response
    {
        $usuario = $this->getUser();

        return $this->render('security/perfil.html.twig', [
            'usuario' => $usuario,
        ]);
    }

    #[Route('/usuario/editar', name: 'editar_datos_usuario')]
    public function editarDatosUsuario(UsuarioManager $usuarioManager, Request $request): Response
    {
        $usuario = $this->getUser();

        if ($request->isMethod('POST')) {
            $nombre = $request->request->get('nombre');
            $apellido = $request->request->get('apellido');
            $dni = $request->request->get('dni');
            $email = $request->request->get('email');

            $usuarioManager->actualizarUsuario($usuario, $nombre, $apellido, $dni, $email);

            return $this->redirectToRoute('ver_datos_usuario');
        }

        return $this->render('security/editar_perfil.html.twig', [
            'usuario' => $usuario,
        ]);
    }
}
