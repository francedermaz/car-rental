<?php

namespace App\Manager;

use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;

class UsuarioManager
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    function registrarUsuario($nombre, $apellido, $dni, $email, $password)
    {
        $usuario = new Usuario();
        $usuario->setNombre($nombre);
        $usuario->setApellido($apellido);
        $usuario->setDni($dni);
        $usuario->setEmail($email);
        $usuario->setPassword($password);
        $usuario->setRol("user");
        
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $usuario->setPassword($hashedPassword);

        $this->entityManager->persist($usuario);
        $this->entityManager->flush();
    }

}