<?php

namespace App\Controller\Dashboard;

use App\Entity\Partner;
use App\Form\PartnerType;
use App\Repository\PartnerRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/partner')]
class PartnerController extends AbstractController
{
    #[Route('/', name: 'partner', methods: ['GET'])]
    public function index(PartnerRepository $partnerRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $data = $partnerRepository->findAll();

        $partners = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            6
        );
        return $this->render('partner/index.html.twig', [
            'partners' => $partners,
        ]);
    }

    #[Route('/new', name: 'partner_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PartnerRepository $partnerRepository): Response
    {
        $partner = new Partner();
        $form = $this->createForm(PartnerType::class, $partner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $partnerRepository->save($partner, true);

            return $this->redirectToRoute('partner', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('partner/new.html.twig', [
            'partner' => $partner,
            'partnerForm' => $form,
        ]);
    }

    #[Route('/', name: 'partner_show', methods: ['GET'])]
    public function show(Partner $partner): Response
    {
        return $this->render('partner/show.html.twig', [
            'partner' => $partner,
        ]);
    }

    #[Route('/{id}/edit', name: 'partner_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Partner $partner, PartnerRepository $partnerRepository): Response
    {
        $form = $this->createForm(PartnerType::class, $partner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $partnerRepository->save($partner, true);

            return $this->redirectToRoute('partner', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('partner/edit.html.twig', [
            'partner' => $partner,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'partner_delete', methods: ['POST'])]
    public function delete(Request $request, Partner $partner, PartnerRepository $partnerRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$partner->getId(), $request->request->get('_token'))) {
            $partnerRepository->remove($partner, true);
        }

        return $this->redirectToRoute('app_partner_index', [], Response::HTTP_SEE_OTHER);
    }
}
