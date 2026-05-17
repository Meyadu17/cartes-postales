<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Traits\FormErrorsTrait;
use App\Entity\Region;
use App\Form\RegionType;
use App\Repository\RegionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/admin/mon-espace/regions', name: 'app_region_')]
final class RegionController extends AbstractController
{
    use FormErrorsTrait;

     public function __construct(
        private RegionRepository $regionRepository,
        private EntityManagerInterface $em,
    ) {}

    // =====================
    // DONNÉES
    // =====================
    #[Route('/data', name: 'data', methods: ['GET'])]
    public function data(): JsonResponse
    {
        $data = array_map(fn(Region $r) => [
            'id'          => $r->getId(),
            'code'        => $r->getCode(),
            'nom'         => $r->getNom(),
            'description' => $r->getDescription(),
        ], $this->regionRepository->findAllOrderedByNom());

        return $this->json($data);
    }

    // =====================
    // CRÉATION
    // =====================
    #[Route('/new', name: 'new', methods: ['POST'])]
    public function new(Request $request): JsonResponse
    {
        $region = new Region();
        $form = $this->createForm(RegionType::class, $region);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->regionRepository->save($region, true);
            return $this->json(['success' => true, 'id' => $region->getId()]);
        }

        return $this->json([
            'success' => false,
            'errors'  => $this->getFormErrors($form),
        ], 422);
    }

    // =====================
    // SUPPRESSION
    // =====================
    #[Route('/{id}/delete', name: 'delete', methods: ['DELETE'])]
    public function delete(Region $region): JsonResponse
    {
        $this->em->remove($region);
        $this->em->flush();

        return $this->json(['success' => true]);
    }

    #[Route('/{id}/dependances', name: 'dependances', methods: ['GET'])]
    public function dependances(Region $region): JsonResponse
    {
        $departements = array_map(function ($dept) {
            return [
                'nom'    => $dept->getNom(),
                'villes' => array_map(
                    fn($ville) => $ville->getNom(),
                    $dept->getVilles()->toArray()
                ),
            ];
        }, $region->getDepartements()->toArray());

        return $this->json(['departements' => $departements]);
    }

    // =====================
    // CONSULTER
    // =====================
    #[Route('/{code}', name: 'consult')]
    public function consult(string $code): Response
    {
        $region = $this->regionRepository->findOneByCode($code);

        if (!$region) {
            throw $this->createNotFoundException('Région introuvable');
        }

        return $this->render('region/region_page.html.twig', [
            'region' => $region,
        ]);
    }

    // =====================
    // MODIFICATION
    // =====================
    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Region $region): JsonResponse
    {
        // GET : retourne les données pour pré-remplir la modale
        if ($request->isMethod('GET')) {
            return $this->json([
                'id'          => $region->getId(),
                'code'        => $region->getCode(),
                'nom'         => $region->getNom(),
                'description' => $region->getDescription(),
            ]);
        }

        // POST : traite la soumission
        $form = $this->createForm(RegionType::class, $region);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            return $this->json(['success' => true]);
        }

        return $this->json([
            'success' => false,
            'errors'  => $this->getFormErrors($form),
        ], 422);
    }

}
