<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Traits\FormErrorsTrait;
use App\Entity\Departement;
use App\Entity\Ville;
use App\Form\VilleType;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/mon-espace/villes', name: 'app_ville_')]
final class VilleController extends AbstractController
{
    use FormErrorsTrait;
    public function __construct(
        private VilleRepository $villeRepository,
        private EntityManagerInterface $em,
    ) {}

    // =====================
    // DONNÉES
    // =====================
    #[Route('/data', name: 'data', methods: ['GET'])]
    public function data(): JsonResponse
    {
        $data = array_map(fn(Ville $v) => [
            'id'          => $v->getId(),
            'nom'         => $v->getNom(),
            'codePostal'  => $v->getCodePostal(),
            'departement' => $v->getDepartement()?->getNom(),
        ], $this->villeRepository->findAllOrderedByNom());

        return $this->json($data);
    }


    // =====================
    // RÉCUPÉRER VILLES PAR DÉPARTEMENT
    // =====================
    #[Route('/par-departement/{id}', name: 'by_departement', methods: ['GET'])]
    public function villesByDepartement(Departement $departement, VilleRepository $villeRepo): JsonResponse
    {
        $villes = $villeRepo->findByDepartementOrderedByNom($departement);

        $data = array_map(fn(Ville $v) => [
            'id' => $v->getId(),
            'nom' => $v->getNom(),
        ], $villes);

        return $this->json($data);
    }

    // =====================
    // CRÉATION
    // =====================
    #[Route('/new', name: 'new', methods: ['POST'])]
    public function new(Request $request): JsonResponse
    {
        $ville = new Ville();
        $form = $this->createForm(VilleType::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->villeRepository->save($ville, true);
            return $this->json(['success' => true, 'id' => $ville->getId()]);
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
    public function delete(Ville $ville): JsonResponse
    {
        $this->em->remove($ville);
        $this->em->flush();

        return $this->json(['success' => true]);
    }

    // =====================
    // MODIFICATION
    // =====================
    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ville $ville): JsonResponse
    {
        if ($request->isMethod('GET')) {
            return $this->json([
                'id'           => $ville->getId(),
                'nom'          => $ville->getNom(),
                'departementId' => $ville->getDepartement()?->getId(),
            ]);
        }

        $form = $this->createForm(VilleType::class, $ville);
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
