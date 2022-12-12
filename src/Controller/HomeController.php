<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\TrainingRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @throws NonUniqueResultException
     */
    #[Route('/', name: 'app_homepage')]
    public function homepage(EventRepository $eventRepository, TrainingRepository $trainingRepository): Response
    {
        return $this->render('home/homepage.html.twig', [
            'event' => $eventRepository->findNextEvent(),
            'training' => $trainingRepository->findNextTraining(),
        ]);
    } // homepage
} // HomeController
