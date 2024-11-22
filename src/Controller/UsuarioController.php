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
            $password = $request->request->get('password');

            if ($email !== $usuario->getEmail() && $usuarioManager->existeEmail($email)) {
                $this->addFlash('error', 'El correo electrónico ya está registrado por otro usuario.');
                return $this->redirectToRoute('editar_datos_usuario');
            }

            $usuarioManager->actualizarUsuario($usuario, $nombre, $apellido, $dni, $email, $password);
            $this->addFlash('success', '¡Tus datos se modificaron con éxito!');

            return $this->redirectToRoute('ver_datos_usuario');
        }

        return $this->render('security/editar_perfil.html.twig', [
            'usuario' => $usuario,
        ]);
    }
}