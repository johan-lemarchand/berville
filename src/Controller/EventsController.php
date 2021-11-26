<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventsController extends AbstractController
{
    /**
     * @Route("/event", name="event")
     */
    public function index(): Response
    {
        return $this->render('maps/index.html.twig', [
            'controller_name' => 'EventsController',
        ]);
    }
}
