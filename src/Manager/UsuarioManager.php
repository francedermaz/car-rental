<?php

namespace App\Manager;

use App\Repository\UsuarioRepository;
use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsuarioManager
{
    private $entityManager;
    private $passwordHasher;
    private $usuarioRepository;

    public function __construct(UsuarioRepository $usuarioRepository, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        $this->usuarioRepository = $usuarioRepository;
    }

    public function existeEmail($email)
    {
        $usuario = $this->usuarioRepository->findOneBy(['email' => $email]);
        if ($usuario) {
            return true;
        } else {
            return false;
        }
    }

    public function registrarUsuario($nombre, $apellido, $dni, $email, $password): bool
    {
        if ($this->existeEmail($email)) {
            return false; 
        }

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

        return true;
    }

    public function actualizarUsuario(Usuario $usuario, string $nombre, string $apellido, string $dni, string $email, string $password): void
    {
        $usuario->setNombre($nombre);
        $usuario->setApellido($apellido);
        $usuario->setDni($dni);
        $usuario->setEmail($email);

        if ($password) {
            $hashedPassword = $this->passwordHasher->hashPassword($usuario, $password);
            $usuario->setPassword($hashedPassword);
        }

        $this->entityManager->persist($usuario);
        $this->entityManager->flush();
    }
}