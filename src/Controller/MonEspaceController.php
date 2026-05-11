<?php

namespace App\Controller;

use App\Entity\Departement;
use App\Entity\Region;
use App\Form\DepartementType;
use App\Form\RegionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(Request $request): Response
    {
        // Formulaire création région
        $region = new Region();
        $formRegion = $this->createForm(RegionType::class, $region);
        $formRegion->handleRequest($request);

        if ($formRegion->isSubmitted() && $formRegion->isValid()) {
            $this->regionRepository->save($region, true);

            return $this->redirectToRoute('app_mon_espace');
        }
        // Fin du formulaire de création de région

        // Formulaire création département
        $departement = new Departement();
        $formDepartement = $this->createForm(DepartementType::class, $departement);
        $formDepartement->handleRequest($request);

        if ($formDepartement->isSubmitted() && $formDepartement->isValid()) {
            $this->departementRepository->save($departement, true);
            return $this->redirectToRoute('app_mon_espace');
        }
        // Fin du formulaire de création de département


        return $this->render('mon_espace/index.html.twig', [
            'regions' => $this->regionRepository->findAll(),
            'departements' => $this->departementRepository->findAll(),
            'villes' => $this->villeRepository->findAll(),

            'form_region' => $formRegion,
            'form_departement' => $formDepartement,
        ]);
    }

}