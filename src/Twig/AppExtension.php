<?php

namespace App\Twig;

use App\Repository\RegionRepository;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
    public function __construct(
        private RegionRepository $regionRepository
    ) {}

    public function getGlobals(): array
    {
        return [
            'regions_menu' => $this->regionRepository->findBy([], ['nom' => 'ASC']),
        ];
    }
}
