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

            $usuarioManager->registrarUsuario($nombre, $apellido, $dni, $email, $password);
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/register.html.twig');
    }
}