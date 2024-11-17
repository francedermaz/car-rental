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
                'detalle' => 'Un sedán confiable y eficiente en combustible.',
                'year' => 2020,
                'imagen' => 'https://media.toyota.com.ar/b90e14a0-dca9-410b-a2e9-ffd399d5edd1.png',
                'valor' => 25000.00,
            ],
            [
                'marca' => 'Ford',
                'modelo' => 'Mustang',
                'detalle' => 'Un deportivo clásico con excelente desempeño.',
                'year' => 2022,
                'imagen' => 'https://www.vdm.ford.com/content/dam/na/ford/en_us/images/mustang/2025/jellybeans/Ford_Mustang_2025_600A_PYZ_882_89A_13K_COU_64R_67J_990_19X_18Z_91A_17P_44E_MAC_DEFAULT_EXT_4.png',
                'valor' => 55000.00,
            ],
            [
                'marca' => 'Chevrolet',
                'modelo' => 'Camaro',
                'detalle' => 'Un muscle car potente y estilizado.',
                'year' => 2021,
                'imagen' => 'https://acnews.blob.core.windows.net/imgnews/medium/NAZ_b687ac855f1d4649ae66e74216f4d4e8.webp',
                'valor' => 50000.00,
            ],
            [
                'marca' => 'Fiat',
                'modelo' => '500',
                'detalle' => 'Un muscle car potente y estilizado.',
                'year' => 2021,
                'imagen' => 'https://www.media.stellantis.com/cache/3/2/8/f/1/328f152f90860e776c92fc0445dec9a4343a10c5.jpeg',
                'valor' => 50000.00,
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
