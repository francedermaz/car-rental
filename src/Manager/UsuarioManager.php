<?php

namespace App\Manager;

use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsuarioManager
{
    private $entityManager;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    public function registrarUsuario($nombre, $apellido, $dni, $email, $password): void
    {
        $usuario = new Usuario();
        $usuario->setNombre($nombre);
        $usuario->setApellido($apellido);
        $usuario->setDni($dni);
        $usuario->setEmail($email);
        $usuario->setRol("ROLE_USER");

        $hashedPassword = $this->passwordHasher->hashPassword($usuario, $password);
        $usuario->setPassword($hashedPassword);

        $this->entityManager->persist($usuario);
        $this->entityManager->flush();
    }

    public function actualizarUsuario(Usuario $usuario, string $nombre, string $apellido, string $dni): void
    {
        $usuario->setNombre($nombre);
        $usuario->setApellido($apellido);
        $usuario->setDni($dni);

        $this->entityManager->persist($usuario);
        $this->entityManager->flush();
    }
}
