<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Traits\FormErrorsTrait;
use App\Entity\Departement;
use App\Entity\Region;
use App\Entity\Ville;
use App\Form\DepartementType;
use App\Form\RegionType;
use App\Form\VilleType;
use App\Repository\DepartementRepository;
use App\Repository\RegionRepository;
use App\Repository\VilleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/mon-espace', name: 'mon_espace_')]
final class MonEspaceController extends AbstractController
{
    use FormErrorsTrait;
    public function __construct(
        private RegionRepository $regionRepository,
        private DepartementRepository $departementRepository,
        private VilleRepository $villeRepository,
    ) {}

    #[Route('', name: 'index')]
    public function index(): Response
    {
        return $this->render('mon_espace/mon_espace_page.html.twig', [
            // Formulaires création
            'form_region'           => $this->createForm(RegionType::class, new Region())->createView(),
            'form_departement'      => $this->createForm(DepartementType::class, new Departement())->createView(),
            'form_ville'            => $this->createForm(VilleType::class, new Ville())->createView(),
            // Formulaires édition (vides, remplis via JS)
            'form_region_edit'      => $this->createForm(RegionType::class, new Region())->createView(),
            'form_departement_edit' => $this->createForm(DepartementType::class, new Departement())->createView(),
            'form_ville_edit'       => $this->createForm(VilleType::class, new Ville())->createView(),
        ]);
    }
}
