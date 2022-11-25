<?php

namespace App\Controller;

use App\Repository\EventRepository;

use Doctrine\DBAL\Exception\DatabaseDoesNotExist;
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

            $eventId = json_decode($request->getContent(), true);
            $event = $eventRepository->findAllByEventId($eventId);


            $render = $this->render('maps/card.html.twig', [
                'events' => $event,
            ]);

            return $this->json($render, 200);
        }

        return $this->render('maps/index.html.twig', [

        ]);
    } // index
} // EventsController
