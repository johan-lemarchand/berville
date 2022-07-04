<?php

namespace App\Controller;

use App\Entity\Event;
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
        $events = $this->getDoctrine()->getRepository(Event::class)->findAll();

        return $this->render('maps/index.html.twig', [
            'events' => $events,
        ]);
    }
}
