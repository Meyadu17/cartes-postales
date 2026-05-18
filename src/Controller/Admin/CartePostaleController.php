<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Traits\FormErrorsTrait;
use App\Entity\CartePostale;
use App\Form\CartePostaleType;
use App\Repository\CartePostaleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/mon-espace/cartes-postales', name: 'app_postcard_')]
final class CartePostaleController extends AbstractController
{
    use FormErrorsTrait;

    public function __construct(
        private CartePostaleRepository $cartePostaleRepository,
        private EntityManagerInterface $em,
    ) {}

    // =====================
    // DONNÉES
    // =====================
    #[Route('/data', name: 'data', methods: ['GET'])]
    public function data(): JsonResponse
    {
        $data = array_map(fn(CartePostale $c) => [
            'id'          => $c->getId(),
            'titre'       => $c->getTitre(),
            'description' => $c->getDescription(),
            'annee'       => $c->getAnnee(),
            'orientation' => $c->getOrientation(),
            'enCaroussel' => $c->isEnCaroussel(),
            'imageNom'    => $c->getLogoNom(),
            'imagePath'   => $c->getLogoPath(),
            'ville'       => $c->getVille()?->getNom(),
            'departement' => $c->getDepartement()
                ? $c->getDepartement()->getCode() . ' - ' . $c->getDepartement()->getNom()
                : null,
            'region'      => $c->getRegion()?->getNom(),
            'dateCreation' => $c->getDateCreation()->format('Y-m-d H:i:s'),
        ], $this->cartePostaleRepository->findBy([], ['dateCreation' => 'DESC']));

        return $this->json($data);
    }

    // =====================
    // CRÉATION
    // =====================
    #[Route('/new', name: 'new', methods: ['POST'])]
    public function new(Request $request): JsonResponse
    {
        $carte = new CartePostale();
        $carte->setDateCreation(new \DateTimeImmutable());

        $form = $this->createForm(CartePostaleType::class, $carte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // ── Traitement de l'image (base64) ──────────────────────────
            $base64 = $form->get('logoBase64')->getData();

            if (!empty($base64)) {
                $imageNom = $this->sauvegarderLogo($base64);

                if ($imageNom !== null) {
                    $carte->setLogoNom($imageNom);
                }
            }

            // Sécurité : si pas d'image fournie, on refuse
            if (empty($carte->getLogoNom())) {
                return $this->json([
                    'success' => false,
                    'errors'  => ['image' => 'Une image est requise.'],
                ], 422);
            }
            // ────────────────────────────────────────────────────────────

            $this->cartePostaleRepository->save($carte, true);

            return $this->json(['success' => true, 'id' => $carte->getId()]);
        }

        return $this->json([
            'success' => false,
            'errors'  => $this->getFormErrors($form),
        ], 422);
    }

    // =====================
    // ÉDITION
    // =====================
    #[Route('/{id}/edit', name: 'edit', methods: ['POST'])]
    public function edit(Request $request, CartePostale $carte): JsonResponse
    {
        $form = $this->createForm(CartePostaleType::class, $carte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $base64 = $form->get('logoBase64')->getData();

            if (!empty($base64)) {
                // Supprimer l'ancienne image
                if ($carte->getLogoNom()) {
                    $this->supprimerLogo($carte->getLogoNom());
                }

                $imageNom = $this->sauvegarderLogo($base64);
                if ($imageNom !== null) {
                    $carte->setLogoNom($imageNom);
                }
            }

            $this->em->flush();

            return $this->json(['success' => true, 'id' => $carte->getId()]);
        }

        return $this->json([
            'success' => false,
            'errors'  => $this->getFormErrors($form),
        ], 422);
    }

    // =====================
    // SUPPRESSION
    // =====================
    #[Route('/{id}/delete', name: 'delete', methods: ['POST', 'DELETE'])]
    public function delete(CartePostale $carte): JsonResponse
    {
        if ($carte->getLogoNom()) {
            $this->supprimerLogo($carte->getLogoNom());
        }

        $this->em->remove($carte);
        $this->em->flush();

        return $this->json(['success' => true]);
    }

    // =====================
    // MÉTHODES PRIVÉES
    // =====================

    /**
     * Décode un base64 et sauvegarde le fichier dans /public/uploads/cartes/
     * Retourne le nom du fichier généré.
     */
    private function sauvegarderLogo(string $base64): ?string
    {
        if (!str_contains($base64, ',')) {
            return null;
        }

        $data = explode(',', $base64);

        if (empty($data[1])) {
            return null;
        }

        // Détecte l'extension depuis le header (data:image/jpeg;base64,...)
        $extension = 'png';
        if (preg_match('/data:image\/(\w+);base64/', $data[0], $matches)) {
            $ext = strtolower($matches[1]);
            $extension = in_array($ext, ['jpeg', 'jpg', 'png', 'webp', 'gif']) ? $ext : 'png';
        }

        $imageData = base64_decode($data[1]);

        if ($imageData === false) {
            return null;
        }

        $nomFichier = uniqid('carte_', true) . '.' . $extension;
        $dossier = $this->getParameter('kernel.project_dir') . '/public/' . CartePostale::LOGO_DIR . '/';

        if (!is_dir($dossier)) {
            mkdir($dossier, 0755, true);
        }

        file_put_contents($dossier . $nomFichier, $imageData);

        return $nomFichier;
    }

    /**
     * Supprime un fichier image du disque.
     */
    private function supprimerLogo(string $nomFichier): void
    {
        $chemin = $this->getParameter('kernel.project_dir') . '/public/' . CartePostale::LOGO_DIR . '/' . $nomFichier;

        if (file_exists($chemin)) {
            unlink($chemin);
        }
    }
}
