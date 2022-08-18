<?php

namespace App\Controller;

use App\Entity\Images;

use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{
    JsonResponse,
    Request
};
use Symfony\Component\Routing\Annotation\Route;


class ImagesController extends AbstractController
{
    #[Route('/delete/images/avatar/{id}', name: 'delete-avatar', methods: ['DELETE'])]
    public function deleteAvatar(Images $image, Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // On vérifie si le token est valide
        if($this->isCsrfTokenValid('delete'.$image->getId(), $data['_token'])){
            // On récupère le nom de l'image
            $nom = $image->getName();
            // On supprime le fichier
            unlink($this->getParameter('avatar_directory').'/'.$nom);

            // On supprime l'entrée de la base
            $em = $doctrine->getManager();
            $em->remove($image);
            $em->flush();

            // On répond en json
            return new JsonResponse(['success' => 1]);
        }else{
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    } // deleteAvatar
} // ImagesController
