<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Facturation;
use App\Form\FacturationType;
use App\Repository\BookingRepository;
use App\Repository\FacturationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/facturations")
 */
class FacturationsController extends AbstractController
{
    /**
     * @Route("/liste-facturation", name="liste_facturation", methods={"GET"})
     * @param FacturationRepository $facturationRepository
     * @return Response
     */
    public function index(FacturationRepository $facturationRepository): Response
    {

//        dd($facturationRepository->findAll()[0]->getBooking()->getClients()[0]->getNom());
        return $this->render('facturations/index.html.twig', [
            'facturations' => $facturationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/creation-facture", name="creation_facture", methods={"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param RequestStack $requestStack
     * @param BookingRepository $bookingRepository
     * @return Response
     */
    public function facture(Request $request, EntityManagerInterface $entityManager,RequestStack $requestStack,BookingRepository  $bookingRepository): Response
    {

        $session = $requestStack->getSession();
         $filters = $session->get('booking_new', []);

//         dd($filters->getid());
        $facturation = new Facturation();
        $form = $this->createForm(FacturationType::class, $facturation);
        $form->handleRequest($request);

        $facturation->setCreatedAd(new \DateTime('today'));

        $booking= $bookingRepository->find($filters->getid());

//        dd($booking);


        $facturation->setBooking($booking);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($facturation);
            $entityManager->flush();

//            dd($facturation);

            return $this->redirectToRoute('liste_facturation', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('facturations/new.html.twig', [
            'facturation' => $facturation,
            'form' => $form,
            'reservation'=>"booking"
        ]);
    }

    /**
     * @Route("/detail-facture/{id}", name="facturations_detail", methods={"GET"})
     * @param Facturation $facturation
     * @return Response
     */
    public function show(Facturation $facturation): Response
    {
        return $this->render('facturations/show.html.twig', [
            'facturation' => $facturation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="facturations_edit", methods={"GET", "POST"})
     * @param Request $request
     * @param Facturation $facturation
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function edit(Request $request, Facturation $facturation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FacturationType::class, $facturation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('facturations_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('facturations/edit.html.twig', [
            'facturation' => $facturation,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="facturations_delete", methods={"POST"})
     * @param Request $request
     * @param Facturation $facturation
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function delete(Request $request, Facturation $facturation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$facturation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($facturation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('facturations_index', [], Response::HTTP_SEE_OTHER);
    }
}
