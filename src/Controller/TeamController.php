<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends AbstractController
{
    #[Route('/team', name: 'team')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAllIsVerified();

        return $this->render('team/index.html.twig', [
            'users' => $users,
        ]);
    }
}
