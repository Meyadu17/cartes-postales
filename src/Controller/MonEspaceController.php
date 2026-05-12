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
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/admin/mon-espace')]
final class MonEspaceController extends AbstractController
{
    public function __construct(
        private RegionRepository $regionRepository,
        private DepartementRepository $departementRepository,
        private VilleRepository $villeRepository,
        private EntityManagerInterface $em,
    ) {}

    // =====================
    // PAGE PRINCIPALE
    // =====================
    #[Route('', name: 'app_mon_espace')]
    public function index(): Response
    {
        return $this->render('mon_espace/index.html.twig', [
            'regions'          => $this->regionRepository->findAllOrderedByNom(),
            'departements'     => $this->departementRepository->findAllOrderedByNom(),
            'villes'           => $this->villeRepository->findAllOrderedByNom(),

            'form_region'      => $this->createForm(RegionType::class, new Region())->createView(),
            'form_departement' => $this->createForm(DepartementType::class, new Departement())->createView(),
            'form_ville'       => $this->createForm(VilleType::class, new Ville())->createView(),

            'form_region_edit' =>  $this->createForm(RegionType::class, new Region())->createView(),
            'form_departement_edit' => $this->createForm(DepartementType::class, new Departement())->createView(),
            'form_ville_edit' => $this->createForm(VilleType::class, new Ville())->createView(),
        ]);
    }

    // =====================
    // DONNÉES POUR LES DATATABLES
    // =====================
    #[Route('/data/regions', name: 'app_data_regions')]
    public function dataRegions(): JsonResponse
    {
        $data = array_map(fn($r) => [
            'code' => $r->getCode(),
            'nom'  => $r->getNom(),
            'id'   => $r->getId(),
        ], $this->regionRepository->findAllOrderedByNom());

        return $this->json($data);
    }

    #[Route('/data/departements', name: 'app_data_departements')]
    public function dataDepartements(): JsonResponse
    {
        $data = array_map(fn($d) => [
            'code'   => $d->getCode(),
            'nom'    => $d->getNom(),
            'region' => $d->getRegion()?->getNom(),
            'id'     => $d->getId(),
        ], $this->departementRepository->findAllOrderedByNom());

        return $this->json($data);
    }

    #[Route('/data/villes', name: 'app_data_villes')]
    public function dataVilles(): JsonResponse
    {
        $data = array_map(fn($v) => [
            'nom'         => $v->getNom(),
            'departement' => $v->getDepartement()?->getNom(),
            'id'          => $v->getId(),
        ], $this->villeRepository->findAllOrderedByNom());

        return $this->json($data);
    }

    // =====================
    // CRÉATION
    // =====================
    #[Route('/region', name: 'app_region_new', methods: ['POST'])]
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

    #[Route('/departement', name: 'app_departement_new', methods: ['POST'])]
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

    #[Route('/ville', name: 'app_ville_new', methods: ['POST'])]
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

    // =====================
    // MODIFICATION
    // =====================
    #[Route('/region/{id}/edit', name: 'app_region_edit', methods: ['GET', 'POST'])]
    public function editRegion(Request $request, Region $region): Response
    {
        $form = $this->createForm(RegionType::class, $region);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            return $this->json(['success' => true]);
        }

        return $this->render('components/_edit_form.html.twig', [
            'form'        => $form->createView(),
            'modal_id'    => 'modalEditerRegion',
            'modal_title' => 'Modifier une région',
            'action_url'  => $this->generateUrl('app_region_edit', ['id' => $region->getId()]),
        ]);
    }

    #[Route('/departement/{id}/edit', name: 'app_departement_edit', methods: ['GET', 'POST'])]
    public function editDepartement(Request $request, Departement $departement): Response
    {
        $form = $this->createForm(DepartementType::class, $departement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            return $this->json(['success' => true]);
        }

        return $this->render('components/_edit_form.html.twig', [
            'form'        => $form->createView(),
            'modal_id'    => 'modalEditerDepartement',
            'modal_title' => 'Editer un département',
            'action_url'  => $this->generateUrl('app_departement_edit', ['id' => $departement->getId()]),
        ]);
    }

    #[Route('/ville/{id}/edit', name: 'app_ville_edit', methods: ['GET', 'POST'])]
    public function editVille(Request $request, Ville $ville): Response
    {
        $form = $this->createForm(VilleType::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            return $this->json(['success' => true]);
        }

        return $this->render('components/_edit_form.html.twig', [
            'form'        => $form->createView(),
            'modal_id'    => 'modalEditerVille',
            'modal_title' => 'Editer une ville',
            'action_url'  => $this->generateUrl('app_ville_edit', ['id' => $ville->getId()]),
        ]);
    }

    // =====================
    // SUPRESSION
    // =====================
    #[Route('/suppression/region/{id}', name: 'admin_api_region_delete', methods: ['DELETE'])]
    public function regionDelete(Region $region): JsonResponse
    {
        $this->em->remove($region);
        $this->em->flush();

        return $this->json(['success' => true]);
    }

    #[Route('/suppression/departement/{id}', name: 'admin_api_departement_delete', methods: ['DELETE'])]
    public function departementDelete(Departement $departement): JsonResponse
    {
        $this->em->remove($departement);
        $this->em->flush();

        return $this->json(['success' => true]);
    }

    #[Route('/suppression/ville/{id}', name: 'admin_api_ville_delete', methods: ['DELETE'])]
    public function villeDelete(Ville $ville): JsonResponse
    {
        $this->em->remove($ville);
        $this->em->flush();

        return $this->json(['success' => true]);
    }

    #[Route('/suppression/region/{id}/dependances', name: 'admin_api_region_dependances')]
    public function regionDependances(Region $region): JsonResponse
    {
        $data = [
            'departements' => [],
        ];

        foreach ($region->getDepartements() as $dept) {
            $villes = [];
            foreach ($dept->getVilles() as $ville) {
                $villes[] = $ville->getNom();
            }
            $data['departements'][] = [
                'nom' => $dept->getNom(),
                'villes' => $villes,
            ];
        }

        return $this->json($data);
    }

    #[Route('/suppression/departement/{id}/dependances', name: 'admin_api_departement_dependances')]
    public function departementDependances(Departement $departement): JsonResponse
    {
        $villes = [];
        foreach ($departement->getVilles() as $ville) {
            $villes[] = $ville->getNom();
        }

        return $this->json(['villes' => $villes]);
    }

}
