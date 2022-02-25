<?php

namespace App\Controller\Dashboard;

use App\Entity\Event;
use App\Entity\Images;
use App\Entity\User;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Geocoder\Exception\Exception;
use Geocoder\Provider\GoogleMaps\GoogleMaps;
use Geocoder\Provider\Nominatim\Nominatim;
use Geocoder\Query\GeocodeQuery;
use Geocoder\StatefulGeocoder;
use Http\Adapter\Guzzle6\Client;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
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

        return $this->render('event/home.html.twig', [
            'events' => $events,
        ]);
    }

    /**
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    #[Route('/new', name: 'event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, FlashyNotifier $flashy, User $user): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        $httpClient = new Client();
        $userAgent = 'https';
        $provider = Nominatim::withOpenStreetMapServer($httpClient, $userAgent);
        $geocoder = new StatefulGeocoder($provider, 'fr');

        if ($form->isSubmitted() && $form->isValid()) {
            $event->setUser($this->getUser());
            $location = $geocoder->geocodeQuery(GeocodeQuery::create($event->getCity() . ', ' . $event->getZip() . ', ' . $event->getPlace()));

            $content = $location->all()[0]->getCoordinates();
            if ($content !== null) {
                $event->setLatitude($content->getLatitude());
                $event->setLongitude($content->getLongitude());
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
            return $this->redirectToRoute('event_show',  ['id' => $event->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('event/edit.html.twig', [
            'event' => $event,
            'eventForm' => $form,
        ]);
    }

    #[Route('/{id}', name: 'event_delete', methods: ['POST'])]
    public function delete(Request $request, Event $event, EntityManagerInterface $entityManager, FlashyNotifier $flashy): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $entityManager->remove($event);
            $entityManager->flush();
        }

        $flashy->success('Votre évènement est bien supprimé');
        return $this->redirectToRoute('event_home', [], Response::HTTP_SEE_OTHER);
    }

 /*   #[Route('/{event_id}/{image_id}', name: 'article_photo_delete')]
    #[Entity('event', options: ['id'=>'event_id'])]
    #[Entity('image', options: ['id'=>'image_id'])]
    public function deletePhoto(Event $event, Images $image, EntityManagerInterface $entityManager, FlashyNotifier $flashy): Response
    {
        $event->removeImage($image);

        $entityManager->persist($event);
        $entityManager->flush();

        $flashy->success('Votre photo est bien supprimée');
        return $this->redirectToRoute('event_show', ['id' => $event->getId()], Response::HTTP_SEE_OTHER);
    }*/
}

