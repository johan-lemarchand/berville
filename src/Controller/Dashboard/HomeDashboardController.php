<?php

namespace App\Controller\Dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeDashboardController extends AbstractController
{
    #[Route('/admin', name: 'home_dashboard')]
    public function index(): Response
    {
        return $this->render('home_dashboard/home.html.twig', [

        ]);
    } // index
} // HomeDashboardController
