<?php

namespace App\Controller;

use App\Repository\RegionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// src/Controller/RegionController.php
#[Route('/region')]
final class RegionController extends AbstractController
{
    public function __construct(
        private RegionRepository $regionRepository,
    ) {}

    #[Route('/{code}', name: 'app_region_consult')]
    public function show(string $code): Response
    {
        $region = $this->regionRepository->findOneByCode($code);

        if (!$region) {
            throw $this->createNotFoundException('Région introuvable');
        }

        return $this->render('region/index.html.twig', [
            'region' => $region,
        ]);
    }
}
