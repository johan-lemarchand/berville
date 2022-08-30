<?php

namespace App\Controller;

use App\Repository\EventRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class EventsController extends AbstractController
{
    #[Route('/event', name: 'event')]
    public function index(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findAllByDate();

        return $this->render('maps/index.html.twig', [
            'events' => $events,
        ]);
    } // index
} // EventsController
