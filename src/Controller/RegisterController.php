<?php

namespace App\Controller;

use App\Manager\UsuarioManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'registrar_usuario')]
    public function registrarUsuario(UsuarioManager $usuarioManager, Request $request): Response 
    {
        if ($request->isMethod('POST')) {
            $nombre = $request->request->get('nombre');
            $apellido = $request->request->get('apellido');
            $dni = $request->request->get('dni');
            $email = $request->request->get('username');
            $password = $request->request->get('password');

            if ($usuarioManager->existeEmail($email)) {
                $this->addFlash('error', 'El correo electrónico ya está registrado.');
                return $this->redirectToRoute('registrar_usuario');
            }

            $usuarioManager->registrarUsuario($nombre, $apellido, $dni, $email, $password);
            $this->addFlash('success', 'Registro exitoso. ¡Ahora puedes iniciar sesión!');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/register.html.twig');
    }
}
