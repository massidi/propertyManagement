<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\BookingType;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reservation")
 */
class ReservationController extends AbstractController
{
    /**
     * @Route("/liste-resercation", name="reservation_index", methods={"GET"})
     * @param BookingRepository $bookingRepository
     * @return Response
     */
    public function index(BookingRepository $bookingRepository): Response
    {
        return $this->render('reservation/index.html.twig', [
            'bookings' => $bookingRepository->findAll(),
        ]);
    }

    /**
     * @Route("/nouvelle_reservation", name="reservation_new", methods={"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($booking);
            $entityManager->flush();

            return $this->redirectToRoute('reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/new.html.twig', [
            'booking' => $booking,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/modifier-reservation/{id}", name="reservation_show", methods={"GET"})
     * @param Booking $booking
     * @return Response
     */
    public function show(Booking $booking): Response
    {
        return $this->render('reservation/show.html.twig', [
            'booking' => $booking,
        ]);
    }

    /**
     * @Route("/modifier-reservation/{id}", name="reservation_edit", methods={"GET", "POST"})
     * @param Request $request
     * @param Booking $booking
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function edit(Request $request, Booking $booking, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/edit.html.twig', [
            'booking' => $booking,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/supprimer-reservation/{id}", name="reservation_delete", methods={"POST"})
     * @param Request $request
     * @param Booking $booking
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function delete(Request $request, Booking $booking, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$booking->getId(), $request->request->get('_token'))) {
            $entityManager->remove($booking);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reservation_index', [], Response::HTTP_SEE_OTHER);
    }
}
