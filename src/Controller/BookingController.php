<?php

namespace App\Controller;

use App\Entity\Appartement;
use App\Entity\Booking;
use App\Form\Booking1Type;
use App\Repository\AppartementRepository;
use App\Repository\BookingRepository;
use Cassandra\Date;
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
     * @param AppartementRepository $appartementRepository
     * @param RequestStack $requestStack
     * @return Response
     * @throws \Exception
     */
    public function booking(FlashyNotifier $notifier, Request $request, EntityManagerInterface $entityManager, Appartement $appartement, AppartementRepository $appartementRepository,RequestStack  $requestStack): Response
    {

        $booking = new Booking();
        $form = $this->createForm(Booking1Type::class, $booking);
        $form->handleRequest($request);
        $booking->setAppartement($appartement);




            // dd($checkInAt->format('Y-m-d H:i'));


        if ($form->isSubmitted() && $form->isValid()) {


            //Check if there are booked Apartment with those dates

            $checkInAt= $form["checkInAt"]->getData()->format('Y-m-d H:i');
            $checkOutAt=$form["checkOutAt"]->getData()->format('Y-m-d H:i');


            $room_availability = $appartementRepository->checkAppartementAvailability($appartement->getId(),$checkInAt, $checkOutAt);

            //Room is available

            if ($room_availability=="0") {




//                $entityManager->persist($booking);
//
//                $entityManager->flush();

                //stores my booking into a session


                $session = $requestStack->getSession();
                $session->set('booking_new', $booking);



                $notifier->success('vous venez de faire une reservation à ' . " " . $booking->getClients()[0]->getNom() . ' avec success');

                // stores an attribute in the session for later reuse


                return $this->redirectToRoute('creation_facture', [], Response::HTTP_SEE_OTHER);
            }
            else{
                //Room is booked
                $notifier->error('veillez choisir une autre date car la date choisie n\'est pas disponible');
                return $this->redirectToRoute('appartement_index', [], Response::HTTP_SEE_OTHER);


            }

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
//dd($bookingRepository->getCurrentBooking());
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
