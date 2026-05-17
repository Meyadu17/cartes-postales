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
        private DepartementRepository  $departementRepository,
        private EntityManagerInterface $em,
    ) {}

    // =====================
    // DONNÉES
    // =====================
    #[Route('/data', name: 'data', methods: ['GET'])]
    public function data(): JsonResponse
    {
        $data = array_map(fn(Departement $d) => [
            'id'      => $d->getId(),
            'code'    => $d->getCode(),
            'nom'     => $d->getNom(),
            'region'  => $d->getRegion()?->getNom(),
            'logoNom' => $d->getLogoNom(),
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

            // ── Traitement du logo (base64) ──────────────────────────────
            $base64 = $form->get('logoBase64')->getData();

            
            // REGION DEBUG temporaire
            /*dump([
                'base64_vide' => empty($base64),
                'base64_debut' => $base64 ? substr($base64, 0, 50) : 'VIDE',
            ]);
            die();*/
            // ENDREGION DEBUG temporaire

            if (!empty($base64)) {
                $logoNom = $this->sauvegarderLogo($base64);

                if ($logoNom !== null) {
                    $departement->setLogoNom($logoNom); 
                }
            }
            // ─────────────────────────────────────────────────────────────

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
        // Suppression du fichier logo si existant
        if ($departement->getLogoNom()) {
            $this->supprimerLogo($departement->getLogoNom());
        }

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
                'logoNom'  => $departement->getLogoNom(),
            ]);
        }

        $form = $this->createForm(DepartementType::class, $departement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // ── Traitement du logo (base64) ──────────────────────────────
            $base64 = $form->get('logoBase64')->getData();

            // On ne traite que si le base64 est une vraie chaîne non vide
            if (!empty($base64)) {
                $logoNom = $this->sauvegarderLogo($base64);

                // sauvegarderLogo() peut retourner null si le base64 est invalide
                if ($logoNom !== null) {
                    if ($departement->getLogoNom()) {          // edit() seulement
                        $this->supprimerLogo($departement->getLogoNom());
                    }
                    $departement->setLogoNom($logoNom);
                }
            }
            // Si base64 vide → on ne touche pas au logoNom existant
            // ─────────────────────────────────────────────────────────────

            $this->em->flush();

            return $this->json(['success' => true]);
        }

        return $this->json([
            'success' => false,
            'errors'  => $this->getFormErrors($form),
        ], 422);
    }

    // =====================
    // MÉTHODES PRIVÉES
    // =====================

    /**
     * Décode un base64 et sauvegarde le fichier dans /public/uploads/departements/
     * Retourne le nom du fichier généré.
     */
    private function sauvegarderLogo(string $base64): ?string
{
    // Vérifie que c'est bien un base64 avec data URI
    if (!str_contains($base64, ',')) {
        return null;
    }

    $data = explode(',', $base64);

    // Vérifie que la partie base64 n'est pas vide
    if (empty($data[1])) {
        return null;
    }

    $imageData = base64_decode($data[1]);

    // Vérifie que le décodage a fonctionné
    if ($imageData === false) {
        return null;
    }

    $nomFichier = uniqid('dept_', true) . '.png';
    $dossier = $this->getParameter('kernel.project_dir') . '/public/uploads/departements/';

    if (!is_dir($dossier)) {
        mkdir($dossier, 0755, true);
    }

    file_put_contents($dossier . $nomFichier, $imageData);

    return $nomFichier;
}

    /**
     * Supprime le fichier logo du disque.
     */
    private function supprimerLogo(string $nomFichier): void
    {
        $chemin = $this->getParameter('kernel.project_dir') . '/public/uploads/departements/' . $nomFichier;

        if (file_exists($chemin)) {
            unlink($chemin);
        }
    }
}
