<?php

namespace App\DataFixtures;

use App\Entity\Vehiculo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class VehiculoFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $vehiculosData = [
            [
                'marca' => 'Toyota',
                'modelo' => 'Corolla',
                'detalle' => 'El Toyota Corolla es un sedán compacto reconocido mundialmente por su confiabilidad, bajo consumo de combustible y excelente relación calidad-precio. Ideal para viajes urbanos y carretera, cuenta con tecnología avanzada en seguridad y un diseño moderno que lo hace destacar en su segmento.',
                'year' => 2020,
                'imagen' => 'https://media.toyota.com.ar/b90e14a0-dca9-410b-a2e9-ffd399d5edd1.png',
                'valor' => 25000.00,
            ],
            [
                'marca' => 'Ford',
                'modelo' => 'Mustang',
                'detalle' => 'El Ford Mustang es un ícono de los muscle cars estadounidenses, combinando potencia y estilo en un diseño atemporal. Equipado con un motor de alto rendimiento, este deportivo ofrece una experiencia de conducción emocionante, junto con opciones tecnológicas de vanguardia y acabados premium.',
                'year' => 2022,
                'imagen' => 'https://www.vdm.ford.com/content/dam/na/ford/en_us/images/mustang/2025/jellybeans/Ford_Mustang_2025_600A_PYZ_882_89A_13K_COU_64R_67J_990_19X_18Z_91A_17P_44E_MAC_DEFAULT_EXT_4.png',
                'valor' => 55000.00,
            ],
            [
                'marca' => 'Chevrolet',
                'modelo' => 'Camaro',
                'detalle' => 'El Chevrolet Camaro es un muscle car que combina un diseño agresivo con tecnología de punta y un desempeño sobresaliente. Perfecto para quienes buscan emoción al volante, su interior cuenta con acabados deportivos y sistemas multimedia avanzados.',
                'year' => 2021,
                'imagen' => 'https://acnews.blob.core.windows.net/imgnews/medium/NAZ_b687ac855f1d4649ae66e74216f4d4e8.webp',
                'valor' => 50000.00,
            ],
            [
                'marca' => 'Fiat',
                'modelo' => '500',
                'detalle' => 'El Fiat 500 es un automóvil compacto con un diseño retro icónico y características modernas. Perfecto para la ciudad, combina maniobrabilidad, eficiencia y estilo único, haciéndolo una excelente opción para aquellos que buscan un auto práctico sin sacrificar personalidad.',
                'year' => 2021,
                'imagen' => 'https://www.media.stellantis.com/cache/3/2/8/f/1/328f152f90860e776c92fc0445dec9a4343a10c5.jpeg',
                'valor' => 17000.00,
            ],
        ];
        
        foreach ($vehiculosData as $data) {
            $vehiculo = new Vehiculo();
            $vehiculo->setMarca($data['marca']);
            $vehiculo->setModelo($data['modelo']);
            $vehiculo->setDetalle($data['detalle']);
            $vehiculo->setYear($data['year']);
            $vehiculo->setImagen($data['imagen']);
            $vehiculo->setValor($data['valor']);

            $manager->persist($vehiculo);
        }

        $manager->flush();
    }
}
