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
    public function new(Request $request, EntityManagerInterface $em): Response {
        $carte = new CartePostale();
        $form = $this->createForm(PostcardType::class, $carte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $carte->setRegion($carte->getDepartement()->getRegion());

            $em->persist($carte);
            $em->flush();

            $this->addFlash('success', 'Carte postale ajoutée avec succès.');

            return $this->redirectToRoute('app_postcard_data');
        }

        return $this->render('admin/postcard_new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
