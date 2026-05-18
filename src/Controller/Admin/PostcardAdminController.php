<?php

namespace App\Controller\Admin;

use App\Entity\CartePostale;
use App\Repository\CartePostaleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/mon-espace/cartes-postales', name: 'app_postcard_')]
class PostcardAdminController extends AbstractController
{
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
}

