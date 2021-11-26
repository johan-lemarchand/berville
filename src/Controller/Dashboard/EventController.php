<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;


#[Route('/admin/event')]
class EventController extends AbstractController
{
    #[Route('/', name: 'event_home', methods: ['GET'])]
    public function index(EventRepository $eventRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $data = $eventRepository->findAllByDate();

        $events = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('event/index.html.twig', [
            'events' => $events,
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[Route('/new', name: 'event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, FlashyNotifier $flashy, HttpClientInterface $client): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $location = $event->getCity() . ', ' . $event->getZip() . ', ' . $event->getPlace();

            $response = $client->request(
                'GET',
                'https://nominatim.openstreetmap.org/search',
                [
                    'query' => [
                        'q' => $location,
                        'format' => 'json',
                        'limit' => '1'
                    ]
                ]
            );
            $content = json_decode((string) ($response->getContent()), true);

            if ($content !== null) {
                $event->setLatitude($content[0]['lat']);
                $event->setLongitude($content[0]['lon']);
            }

            $entityManager->persist($event);
            $entityManager->flush();

            $flashy->success('Votre évènement est bien créé');
            return $this->redirectToRoute('event_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('event/new.html.twig', [
            'event' => $event,
            'eventForm' => $form,
        ]);
    }

    #[Route('/{id}', name: 'event_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/{id}/edit', name: 'event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EntityManagerInterface $entityManager, FlashyNotifier $flashy): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $flashy->success('Votre évènement est bien edité');
            return $this->redirectToRoute('event_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('event/edit.html.twig', [
            'event' => $event,
            'eventForm' => $form,
        ]);
    }

    #[Route('/{id}', name: 'event_delete', methods: ['DELETE'])]
    public function delete(Request $request, Event $event, EntityManagerInterface $entityManager, FlashyNotifier $flashy): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $entityManager->remove($event);
            $entityManager->flush();
        }

        $flashy->success('Votre évènement est bien supprimé');
        return $this->redirectToRoute('event_home', [], Response::HTTP_SEE_OTHER);
    }
}