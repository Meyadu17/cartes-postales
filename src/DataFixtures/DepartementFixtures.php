<?php

namespace App\DataFixtures;

use App\Entity\Departement;
use App\Entity\Region;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DepartementFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $departements = [
            // Auvergne-Rhône-Alpes
            ['nom' => 'Ain',                'code' => '01', 'region' => 'ARA'],
            ['nom' => 'Allier',             'code' => '03', 'region' => 'ARA'],
            ['nom' => 'Ardèche',            'code' => '07', 'region' => 'ARA'],
            ['nom' => 'Cantal',             'code' => '15', 'region' => 'ARA'],
            ['nom' => 'Drôme',              'code' => '26', 'region' => 'ARA'],
            ['nom' => 'Isère',              'code' => '38', 'region' => 'ARA'],
            ['nom' => 'Loire',              'code' => '42', 'region' => 'ARA'],
            ['nom' => 'Haute-Loire',        'code' => '43', 'region' => 'ARA'],
            ['nom' => 'Puy-de-Dôme',        'code' => '63', 'region' => 'ARA'],
            ['nom' => 'Rhône',              'code' => '69', 'region' => 'ARA'],
            ['nom' => 'Savoie',             'code' => '73', 'region' => 'ARA'],
            ['nom' => 'Haute-Savoie',       'code' => '74', 'region' => 'ARA'],
            // Bourgogne-Franche-Comté
            ['nom' => 'Côte-d\'Or',         'code' => '21', 'region' => 'BFC'],
            ['nom' => 'Doubs',              'code' => '25', 'region' => 'BFC'],
            ['nom' => 'Jura',               'code' => '39', 'region' => 'BFC'],
            ['nom' => 'Nièvre',             'code' => '58', 'region' => 'BFC'],
            ['nom' => 'Haute-Saône',        'code' => '70', 'region' => 'BFC'],
            ['nom' => 'Saône-et-Loire',     'code' => '71', 'region' => 'BFC'],
            ['nom' => 'Yonne',              'code' => '89', 'region' => 'BFC'],
            ['nom' => 'Territoire de Belfort', 'code' => '90', 'region' => 'BFC'],
            // Bretagne
            ['nom' => 'Côtes-d\'Armor',     'code' => '22', 'region' => 'BRE'],
            ['nom' => 'Finistère',          'code' => '29', 'region' => 'BRE'],
            ['nom' => 'Ille-et-Vilaine',    'code' => '35', 'region' => 'BRE'],
            ['nom' => 'Morbihan',           'code' => '56', 'region' => 'BRE'],
            // Centre-Val de Loire
            ['nom' => 'Cher',               'code' => '18', 'region' => 'CVL'],
            ['nom' => 'Eure-et-Loir',       'code' => '28', 'region' => 'CVL'],
            ['nom' => 'Indre',              'code' => '36', 'region' => 'CVL'],
            ['nom' => 'Indre-et-Loire',     'code' => '37', 'region' => 'CVL'],
            ['nom' => 'Loir-et-Cher',       'code' => '41', 'region' => 'CVL'],
            ['nom' => 'Loiret',             'code' => '45', 'region' => 'CVL'],
            // Corse
            ['nom' => 'Corse-du-Sud',       'code' => '2A', 'region' => 'COR'],
            ['nom' => 'Haute-Corse',        'code' => '2B', 'region' => 'COR'],
            // Grand Est
            ['nom' => 'Ardennes',           'code' => '08', 'region' => 'GES'],
            ['nom' => 'Aube',               'code' => '10', 'region' => 'GES'],
            ['nom' => 'Marne',              'code' => '51', 'region' => 'GES'],
            ['nom' => 'Haute-Marne',        'code' => '52', 'region' => 'GES'],
            ['nom' => 'Meurthe-et-Moselle', 'code' => '54', 'region' => 'GES'],
            ['nom' => 'Meuse',              'code' => '55', 'region' => 'GES'],
            ['nom' => 'Moselle',            'code' => '57', 'region' => 'GES'],
            ['nom' => 'Bas-Rhin',           'code' => '67', 'region' => 'GES'],
            ['nom' => 'Haut-Rhin',          'code' => '68', 'region' => 'GES'],
            ['nom' => 'Vosges',             'code' => '88', 'region' => 'GES'],
            // Hauts-de-France
            ['nom' => 'Aisne',              'code' => '02', 'region' => 'HDF'],
            ['nom' => 'Nord',               'code' => '59', 'region' => 'HDF'],
            ['nom' => 'Oise',               'code' => '60', 'region' => 'HDF'],
            ['nom' => 'Pas-de-Calais',      'code' => '62', 'region' => 'HDF'],
            ['nom' => 'Somme',              'code' => '80', 'region' => 'HDF'],
            // Île-de-France
            ['nom' => 'Paris',              'code' => '75', 'region' => 'IDF'],
            ['nom' => 'Seine-et-Marne',     'code' => '77', 'region' => 'IDF'],
            ['nom' => 'Yvelines',           'code' => '78', 'region' => 'IDF'],
            ['nom' => 'Essonne',            'code' => '91', 'region' => 'IDF'],
            ['nom' => 'Hauts-de-Seine',     'code' => '92', 'region' => 'IDF'],
            ['nom' => 'Seine-Saint-Denis',  'code' => '93', 'region' => 'IDF'],
            ['nom' => 'Val-de-Marne',       'code' => '94', 'region' => 'IDF'],
            ['nom' => 'Val-d\'Oise',        'code' => '95', 'region' => 'IDF'],
            // Normandie
            ['nom' => 'Calvados',           'code' => '14', 'region' => 'NOR'],
            ['nom' => 'Eure',               'code' => '27', 'region' => 'NOR'],
            ['nom' => 'Manche',             'code' => '50', 'region' => 'NOR'],
            ['nom' => 'Orne',               'code' => '61', 'region' => 'NOR'],
            ['nom' => 'Seine-Maritime',     'code' => '76', 'region' => 'NOR'],
            // Nouvelle-Aquitaine
            ['nom' => 'Charente',           'code' => '16', 'region' => 'NAQ'],
            ['nom' => 'Charente-Maritime',  'code' => '17', 'region' => 'NAQ'],
            ['nom' => 'Corrèze',            'code' => '19', 'region' => 'NAQ'],
            ['nom' => 'Creuse',             'code' => '23', 'region' => 'NAQ'],
            ['nom' => 'Dordogne',           'code' => '24', 'region' => 'NAQ'],
            ['nom' => 'Gironde',            'code' => '33', 'region' => 'NAQ'],
            ['nom' => 'Landes',             'code' => '40', 'region' => 'NAQ'],
            ['nom' => 'Lot-et-Garonne',     'code' => '47', 'region' => 'NAQ'],
            ['nom' => 'Pyrénées-Atlantiques','code' => '64', 'region' => 'NAQ'],
            ['nom' => 'Deux-Sèvres',        'code' => '79', 'region' => 'NAQ'],
            ['nom' => 'Vienne',             'code' => '86', 'region' => 'NAQ'],
            ['nom' => 'Haute-Vienne',       'code' => '87', 'region' => 'NAQ'],
            // Occitanie
            ['nom' => 'Ariège',             'code' => '09', 'region' => 'OCC'],
            ['nom' => 'Aude',               'code' => '11', 'region' => 'OCC'],
            ['nom' => 'Aveyron',            'code' => '12', 'region' => 'OCC'],
            ['nom' => 'Gard',               'code' => '30', 'region' => 'OCC'],
            ['nom' => 'Haute-Garonne',      'code' => '31', 'region' => 'OCC'],
            ['nom' => 'Gers',               'code' => '32', 'region' => 'OCC'],
            ['nom' => 'Hérault',            'code' => '34', 'region' => 'OCC'],
            ['nom' => 'Lot',                'code' => '46', 'region' => 'OCC'],
            ['nom' => 'Lozère',             'code' => '48', 'region' => 'OCC'],
            ['nom' => 'Hautes-Pyrénées',    'code' => '65', 'region' => 'OCC'],
            ['nom' => 'Pyrénées-Orientales','code' => '66', 'region' => 'OCC'],
            ['nom' => 'Tarn',               'code' => '81', 'region' => 'OCC'],
            ['nom' => 'Tarn-et-Garonne',    'code' => '82', 'region' => 'OCC'],
            // Pays de la Loire
            ['nom' => 'Loire-Atlantique',   'code' => '44', 'region' => 'PDL'],
            ['nom' => 'Maine-et-Loire',     'code' => '49', 'region' => 'PDL'],
            ['nom' => 'Mayenne',            'code' => '53', 'region' => 'PDL'],
            ['nom' => 'Sarthe',             'code' => '72', 'region' => 'PDL'],
            ['nom' => 'Vendée',             'code' => '85', 'region' => 'PDL'],
            // Provence-Alpes-Côte d'Azur
            ['nom' => 'Alpes-de-Haute-Provence', 'code' => '04', 'region' => 'PAC'],
            ['nom' => 'Hautes-Alpes',       'code' => '05', 'region' => 'PAC'],
            ['nom' => 'Alpes-Maritimes',    'code' => '06', 'region' => 'PAC'],
            ['nom' => 'Bouches-du-Rhône',   'code' => '13', 'region' => 'PAC'],
            ['nom' => 'Var',                'code' => '83', 'region' => 'PAC'],
            ['nom' => 'Vaucluse',           'code' => '84', 'region' => 'PAC'],
        ];

        foreach ($departements as $data) {
            $departement = new Departement();
            $departement->setNom($data['nom']);
            $departement->setCode($data['code']);
            $departement->setRegion($this->getReference('region-' . $data['region'], Region::class));
            $manager->persist($departement);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [RegionFixtures::class];
    }
}
