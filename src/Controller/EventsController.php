<?php

namespace App\Controller;

use App\Repository\EventRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class EventsController extends AbstractController
{
    #[Route('/event', name: 'event')]
    public function index(Request $request, EventRepository $eventRepository): Response
    {
        if ($request->isXmlHttpRequest()) {
            $date = json_decode($request->getContent(), true);

            $events = $eventRepository->findEventByMonth($date);
            $render = $this->render('maps/card.html.twig', [
                'events' => [...$events],
            ]);

            return $this->json($render, 200);
        }

        $allEvents = $eventRepository->findAllByDate();
        $events = array_filter($allEvents, fn($el) => (
            $el->getDate()->getTimestamp() >= (new \DateTime('NOW'))->setTime(0,0)->getTimestamp()
        ));
        return $this->render('maps/index.html.twig', [
            'events' => [...$events],
        ]);
    }// index
} // EventsController
