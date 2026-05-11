<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\RegionRepository;
use App\Repository\DepartementRepository;
use App\Repository\VilleRepository;

final class MonEspaceController extends AbstractController
{
    public function __construct(
        private RegionRepository $regionRepository,
        private DepartementRepository $departementRepository,
        private VilleRepository $villeRepository,
    ) {}
    
   #[Route('/mon-espace', name: 'app_mon_espace')]
    public function index(): Response
    {
        return $this->render('mon_espace/index.html.twig', [
            'regions' => $this->regionRepository->findAll(),
            'departements' => $this->departementRepository->findAll(),
            'villes' => $this->villeRepository->findAll(),
        ]);
    }

}
