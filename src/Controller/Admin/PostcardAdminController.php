<?php

namespace App\Controller\Admin;

use App\Entity\CartePostale;
use App\Form\PostcardType;
use App\Repository\CartePostaleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/mon-espace/cartes-postales', name: 'app_postcard_')]
class PostcardAdminController extends AbstractController
{

    // =====================
    // DONNÉES
    // =====================
    #[Route('/data', name: 'data', methods: ['GET'])]
    public function data(CartePostaleRepository $repo): JsonResponse
    {
        $cartes = $repo->findAll();

        $data = array_map(function (CartePostale $carte) {
            return [
                'id'          => $carte->getId(),
                'titre'       => $carte->getTitre(),
                'annee'       => $carte->getAnnee() ?? '—',
                'orientation' => $carte->getOrientation(),
                'region'      => $carte->getRegion()?->getNom() ?? '—',
                'departement' => $carte->getDepartement()?->getNom() ?? '—',
                'ville'       => $carte->getVille()?->getNom() ?? '—',
            ];
        }, $cartes);

        return new JsonResponse($data);
    }

    // =====================
    // CRÉATION
    // =====================
    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $em, SluggerInterface $slugger ): Response {
        $carte = new CartePostale();
        $form = $this->createForm(PostcardType::class, $carte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // 1. Région auto depuis le département
            $carte->setRegion($carte->getDepartement()->getRegion());

            // 2. Traitement image base64
            $imageBase64 = $form->get('imageBase64')->getData();
            if ($imageBase64) {
                $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $imageBase64);
                $imageDecoded = base64_decode($imageData);

                // Nom de fichier : slug département + titre + uniqid
                $slug = $slugger->slug(
                    $carte->getDepartement()->getNom() . ' ' . $carte->getTitre()
                )->lower();
                $filename = $slug . '-' . uniqid() . '.webp';

                // Sauvegarde dans public/uploads/cartes/
                $uploadDir = $this->getParameter('kernel.project_dir') . '/public/' . CartePostale::LOGO_DIR;
                file_put_contents($uploadDir . '/' . $filename, $imageDecoded);

                // 3. LogoNom = nom du fichier uniquement
                $carte->setLogoNom($filename);
            }

            $em->persist($carte);
            $em->flush();

            $this->addFlash('success', 'Carte postale ajoutée avec succès.');

            // 4. Redirection selon bouton
            $action = $request->request->get('action');
            if ($action === 'save_and_new') {
                return $this->redirectToRoute('app_postcard_new');
            }

            return $this->redirectToRoute('app_postcard_data');
        }

        return $this->render('admin/postcard_new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
