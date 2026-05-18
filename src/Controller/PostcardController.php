<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PostcardController extends AbstractController
{
    #[Route('/postcard', name: 'app_postcard')]
    public function index(): Response
    {
        return $this->render('postcard/index.html.twig', [
            'controller_name' => 'PostcardController',
        ]);
    }
}
