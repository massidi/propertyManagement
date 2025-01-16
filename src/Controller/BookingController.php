<?php

namespace App\Controller;

use App\Entity\Appartement;
use App\Entity\Booking;
use App\Form\Booking1Type;
use App\Repository\AppartementRepository;
use App\Repository\BookingRepository;
use App\Service\BookingService;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/booking")
 */
class BookingController extends AbstractController
{
    /**
     * @Route("/", name="booking_index", methods={"GET"})
     * @param BookingRepository $bookingRepository
     * @return Response
     */
    public function index(BookingRepository $bookingRepository): Response
    {

        return $this->render('booking/index.html.twig', [
            'bookings' => $bookingRepository->findAll(),
        ]);
    }

    /**
     * @Route("/reservation/nouvelle-reservation/{id}", name="booking_new", methods={"GET", "POST"})
     * @param FlashyNotifier $notifier
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param Appartement $appartement
     * @param BookingService $bookingService
     * @return Response
     */
    public function booking(
        FlashyNotifier $notifier,
        Request $request,
        EntityManagerInterface $entityManager,
        Appartement $appartement,
        BookingService $bookingService
    ): Response {
        $booking = new Booking();
        $form = $this->createForm(Booking1Type::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $checkInAt = $form["checkInAt"]->getData();
            $checkOutAt = $form["checkOutAt"]->getData();

            // Attempt to create the booking
            $isBooked = $bookingService->createBooking($booking, $appartement, $checkInAt, $checkOutAt);

            if ($isBooked) {
                $notifier->success('Vous venez de faire une réservation avec succès.');
                return $this->redirectToRoute('creation_facture', [], Response::HTTP_SEE_OTHER);
            }

            // Explicitly clear session if booking fails
            $bookingService->clearBookingSession();
            $notifier->error('Veuillez choisir une autre date car la date choisie n\'est pas disponible.');
            return $this->redirectToRoute('appartement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('booking/new.html.twig', [
            'booking' => $booking,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/current-booking", name="current-booking", methods={"GET"})
     * @param BookingRepository $bookingRepository
     * @return Response
     */
    public function getCurrentBooking(BookingRepository $bookingRepository): Response
    {
        //here getting the current reservation
        return $this->render('booking/currentBooking.html.twig', [
            'currentBooking' => $bookingRepository->getCurrentBooking(),
        ]);
    }


    /**
     * @Route("/show/{id}", name="booking_show", methods={"GET"})
     */
    public function show(Booking $booking): Response
    {
        return $this->render('booking/show.html.twig', [
            'booking' => $booking,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="booking_edit", methods={"GET", "POST"})
     * @param Request $request
     * @param Booking $booking
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function edit(Request $request, Booking $booking, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Booking1Type::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('booking_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('booking/edit.html.twig', [
            'booking' => $booking,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="booking_delete", methods={"POST"})
     * @param Request $request
     * @param Booking $booking
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function delete(Request $request, Booking $booking, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $booking->getId(), $request->request->get('_token'))) {
            $entityManager->remove($booking);
            $entityManager->flush();
        }

        return $this->redirectToRoute('booking_index', [], Response::HTTP_SEE_OTHER);
    }
}
