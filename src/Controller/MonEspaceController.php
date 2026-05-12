<?php

namespace App\Controller;

use App\Entity\Departement;
use App\Entity\Region;
use App\Entity\Ville;
use App\Form\DepartementType;
use App\Form\RegionType;
use App\Form\VilleType;
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

        // Redirection après la validation
        if ($formRegion->isSubmitted() && $formRegion->isValid()) {
            $this->regionRepository->save($region, true);

            if ($request->request->get('action') === 'save_and_new') {
                return $this->redirect($this->generateUrl('app_mon_espace', ['reopen' => 'region']) . '#region');
            }

            return $this->redirect($this->generateUrl('app_mon_espace') . '#region');
        }

        // Fin du formulaire de création de région

        // Formulaire création département
        $departement = new Departement();
        $formDepartement = $this->createForm(DepartementType::class, $departement);
        $formDepartement->handleRequest($request);

        // Redirection après la validation
        if ($formDepartement->isSubmitted() && $formDepartement->isValid()) {
            $this->departementRepository->save($departement, true);

            if ($request->request->get('action') === 'save_and_new') {
                return $this->redirect($this->generateUrl('app_mon_espace', ['reopen' => 'departement']) . '#departement');
            }

            return $this->redirect($this->generateUrl('app_mon_espace') . '#departement');
        }
        // Fin du formulaire de création de département

        // Formulaire création ville
        $ville = new Ville();
        $formVille = $this->createForm(VilleType::class, $ville);
        $formVille->handleRequest($request);

        // Redirection après la validation
        if ($formVille->isSubmitted() && $formVille->isValid()) {
            $this->villeRepository->save($ville, true);

            if ($request->request->get('action') === 'save_and_new') {
                return $this->redirect($this->generateUrl('app_mon_espace', ['reopen' => 'ville']) . '#ville');
            }

            return $this->redirect($this->generateUrl('app_mon_espace') . '#ville');
        }

        // Fin du formulaire de création de ville

        return $this->render('mon_espace/index.html.twig', [
            'regions' => $this->regionRepository->findAll(),
            'departements' => $this->departementRepository->findAll(),
            'villes' => $this->villeRepository->findAll(),

            'form_region' => $formRegion,
            'form_departement' => $formDepartement,
            'form_ville' => $formVille,
        ]);
    }
}
