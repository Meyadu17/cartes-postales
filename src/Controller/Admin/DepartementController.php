<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Traits\FormErrorsTrait;
use App\Entity\Departement;
use App\Form\DepartementType;
use App\Repository\DepartementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/mon-espace/departements', name: 'app_departement_')]
final class DepartementController extends AbstractController
{
    use FormErrorsTrait;
    public function __construct(
        private DepartementRepository $departementRepository,
        private EntityManagerInterface $em,
    ) {}

    // =====================
    // DONNÉES
    // =====================
    #[Route('/data', name: 'data', methods: ['GET'])]
    public function data(): JsonResponse
    {
        $data = array_map(fn(Departement $d) => [
            'id'     => $d->getId(),
            'code'   => $d->getCode(),
            'nom'    => $d->getNom(),
            'region' => $d->getRegion()?->getNom(),
        ], $this->departementRepository->findAllOrderedByNom());

        return $this->json($data);
    }

    // =====================
    // CRÉATION
    // =====================
    #[Route('/new', name: 'new', methods: ['POST'])]
    public function new(Request $request): JsonResponse
    {
        $departement = new Departement();
        $form = $this->createForm(DepartementType::class, $departement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->departementRepository->save($departement, true);
            return $this->json(['success' => true, 'id' => $departement->getId()]);
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
    public function delete(Departement $departement): JsonResponse
    {
        $this->em->remove($departement);
        $this->em->flush();

        return $this->json(['success' => true]);
    }

    #[Route('/{id}/dependances', name: 'dependances', methods: ['GET'])]
    public function dependances(Departement $departement): JsonResponse
    {
        $villes = array_map(
            fn($ville) => $ville->getNom(),
            $departement->getVilles()->toArray()
        );

        return $this->json(['villes' => $villes]);
    }

    // =====================
    // MODIFICATION
    // =====================
    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Departement $departement): JsonResponse
    {
        if ($request->isMethod('GET')) {
            return $this->json([
                'id'       => $departement->getId(),
                'code'     => $departement->getCode(),
                'nom'      => $departement->getNom(),
                'regionId' => $departement->getRegion()?->getId(),
            ]);
        }

        $form = $this->createForm(DepartementType::class, $departement);
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
