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
use Symfony\Component\HttpFoundation\JsonResponse;

final class MonEspaceController extends AbstractController
{
    public function __construct(
        private RegionRepository $regionRepository,
        private DepartementRepository $departementRepository,
        private VilleRepository $villeRepository,
    ) {}

    // =====================
    // PAGE PRINCIPALE
    // =====================
    #[Route('/mon-espace', name: 'app_mon_espace')]
    public function index(): Response
    {
        return $this->render('mon_espace/index.html.twig', [
            'regions'          => $this->regionRepository->findAll(),
            'departements'     => $this->departementRepository->findAll(),
            'villes'           => $this->villeRepository->findAll(),
            'form_region'      => $this->createForm(RegionType::class, new Region())->createView(),
            'form_departement' => $this->createForm(DepartementType::class, new Departement())->createView(),
            'form_ville'       => $this->createForm(VilleType::class, new Ville())->createView(),
        ]);
    }


    // =====================
    // DONNÉES POUR LES DATATABLES
    // =====================
    #[Route('/mon-espace/data/regions', name: 'app_data_regions')]
    public function dataRegions(): JsonResponse
    {
        $data = array_map(fn($r) => [
            'code' => $r->getCode(),
            'nom'  => $r->getNom(),
            'id'   => $r->getId(),
        ], $this->regionRepository->findAll());

        return $this->json($data);
    }

    #[Route('/mon-espace/data/departements', name: 'app_data_departements')]
    public function dataDepartements(): JsonResponse
    {
        $data = array_map(fn($d) => [
            'code'   => $d->getCode(),
            'nom'    => $d->getNom(),
            'region' => $d->getRegion()?->getNom(),
            'id'     => $d->getId(),
        ], $this->departementRepository->findAll());

        return $this->json($data);
    }

    #[Route('/mon-espace/data/villes', name: 'app_data_villes')]
    public function dataVilles(): JsonResponse
    {
        $data = array_map(fn($v) => [
            'nom'         => $v->getNom(),
            'departement' => $v->getDepartement()?->getNom(),
            'id'          => $v->getId(),
        ], $this->villeRepository->findAll());

        return $this->json($data);
    }

    // =====================
    // CRÉATION
    // =====================
    #[Route('/mon-espace/region', name: 'app_region_new', methods: ['POST'])]
    public function newRegion(Request $request): JsonResponse
    {
        $region = new Region();
        $form = $this->createForm(RegionType::class, $region);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->regionRepository->save($region, true);
            return $this->json(['success' => true]);
        }

        return $this->json(['success' => false], 400);
    }

    #[Route('/mon-espace/departement', name: 'app_departement_new', methods: ['POST'])]
    public function newDepartement(Request $request): JsonResponse
    {
        $departement = new Departement();
        $form = $this->createForm(DepartementType::class, $departement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->departementRepository->save($departement, true);
            return $this->json(['success' => true]);
        }

        return $this->json(['success' => false], 400);
    }

    #[Route('/mon-espace/ville', name: 'app_ville_new', methods: ['POST'])]
    public function newVille(Request $request): JsonResponse
    {
        $ville = new Ville();
        $form = $this->createForm(VilleType::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->villeRepository->save($ville, true);
            return $this->json(['success' => true]);
        }

        return $this->json(['success' => false], 400);
    }
}
