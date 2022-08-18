<?php

namespace App\Controller;

use App\Entity\Event;

use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class EventsController extends AbstractController
{
    #[Route('/event', name: 'event')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $events = $doctrine->getRepository(Event::class)->findAll();

        return $this->render('maps/index.html.twig', [
            'events' => $events,
        ]);
    } // index
} // EventsController
