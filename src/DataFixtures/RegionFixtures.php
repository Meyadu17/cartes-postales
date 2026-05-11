<?php

namespace App\DataFixtures;

use App\Entity\Region;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RegionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $regions = [
            ['nom' => 'Auvergne-Rhône-Alpes',          'code' => 'ARA'],
            ['nom' => 'Bourgogne-Franche-Comté',        'code' => 'BFC'],
            ['nom' => 'Bretagne',                        'code' => 'BRE'],
            ['nom' => 'Centre-Val de Loire',             'code' => 'CVL'],
            ['nom' => 'Corse',                           'code' => 'COR'],
            ['nom' => 'Grand Est',                       'code' => 'GES'],
            ['nom' => 'Hauts-de-France',                 'code' => 'HDF'],
            ['nom' => 'Île-de-France',                   'code' => 'IDF'],
            ['nom' => 'Normandie',                       'code' => 'NOR'],
            ['nom' => 'Nouvelle-Aquitaine',              'code' => 'NAQ'],
            ['nom' => 'Occitanie',                       'code' => 'OCC'],
            ['nom' => 'Pays de la Loire',                'code' => 'PDL'],
            ['nom' => 'Provence-Alpes-Côte d\'Azur',    'code' => 'PAC'],
        ];

        foreach ($regions as $data) {
            $region = new Region();
            $region->setNom($data['nom']);
            $region->setCode($data['code']);
            $manager->persist($region);

            // Référence pour les DepartementFixtures
            $this->addReference('region-' . $data['code'], $region);
        }

        $manager->flush();
    }
}
