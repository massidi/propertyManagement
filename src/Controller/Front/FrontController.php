<?php

namespace App\Controller\Front;

use App\Entity\Appartement;
use App\Entity\Booking;
use App\Entity\Facturation;
use App\Event\SendNotificationEvent;
use App\Form\Booking1Type;
use App\Form\FacturationType;
use App\Repository\AppartementRepository;
use App\Service\BookingService;
use App\Service\PrixService;
use App\Service\RssService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class FrontController extends AbstractController
{
    /**
     * @var FlashyNotifier
     */
    private FlashyNotifier $notifier;
    private BookingService $bookingService;

    public function __construct(FlashyNotifier $notifier,BookingService $bookingService)
    {
        $this->notifier = $notifier;
        $this->bookingService = $bookingService;
    }


    /**
     * @Route("/front-client-liste-appartement", name="appartement_front", methods={"GET", "POST"})
     * @param AppartementRepository $appartementRepository
     * @param RssService $rssService
     * @return Response
     */
    public function Appartement(AppartementRepository $appartementRepository, RssService $rssService): Response
    {
        // Récupérer les appartements depuis la base de données
        $appartements = $appartementRepository->findAll();

        // Appeler la méthode pour récupérer les actualités RSS
        $rssItems = $this->fetchRssItems($rssService);

        // Passer les données des appartements et des actualités RSS à la vue
        return $this->render('front/index.html.twig', [
            'appartements' => $appartements,
            'rssItems' => $rssItems,
        ]);
    }

    /**
     * @Route("/front-client-detail-appartement/{id}", name="appartement_show_front", methods={"GET"})
     * @param Appartement $appartement
     * @return Response
     */
    public function show(Appartement $appartement): Response
    {

//        dd($appartement->getImages());
        return $this->render('front/show.html.twig', [
            'appartement' => $appartement,
        ]);
    }

    /**
     * @Route("/filer-appartement-front", name="filter_appartement_front", methods={"GET"})
     * @param AppartementRepository $appartementRepository
     * @return Response
     * @throws \Exception
     */
    public function filter(AppartementRepository $appartementRepository,Request  $request,RssService $rssService): Response
    {

        $appartements=0;
        $rssItems = $this->fetchRssItems($rssService);


        if ($request->isMethod('GET'))
        {
            $checkIn= new DateTime($request->query->get('checkIn'));
            $checkOut= new DateTime($request->query->get('checkOut'));

            $appartements = $appartementRepository->getAvailableRooms($checkIn->format('Y-m-d H:i:s'),$checkOut->format('Y-m-d H:i:s'));


        }

        return $this->render('front/index.html.twig', [
            'appartements' => $appartements,
            'rssItems' => $rssItems,

        ]);
    }

    private function fetchRssItems(RssService $rssService): array
    {
        // URL du flux RSS de domimmo
        $rssUrl = 'https://www.domimmo.com/rss/annonces/guadeloupe/';

        try {
            // Récupérer les actualités depuis le flux RSS
            return $rssService->RssDomimmo($rssUrl);
        } catch (\Exception $e) {
            // En cas d'erreur, enregistrer le message et retourner un tableau vide
            $this->addFlash('error', 'Erreur lors de la récupération des actualités : ' . $e->getMessage());
            return [];
        }
    }

    /**
     * @Route("/reservation/nouvelle-reservation-front/{id}", name="booking_new_front", methods={"GET", "POST"})
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
                return $this->redirectToRoute('creation_facture_front', [], Response::HTTP_SEE_OTHER);
            }

            // Explicitly clear session if booking fails
            $bookingService->clearBookingSession();
            $notifier->error('Veuillez choisir une autre date car la date choisie n\'est pas disponible.');
            return $this->redirectToRoute('appartement_front', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/booking.html.twig', [
            'booking' => $booking,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/creation-facture-front", name="creation_facture_front", methods={"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param EventDispatcherInterface $dispatcher
     * @param PrixService $prixService
     * @return Response
     */
    public function facture(
        Request $request,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $dispatcher,
        PrixService $prixService
    ): Response {
        try {
            // Use the service to create the booking from the session
            $booking = $this->bookingService->createBookingFromSession();

            // Create the Facturation entity and form
            $facturation = new Facturation();
            $facturation->setCreatedAd(new \DateTime('today'));
            $facturation->setBooking($booking);

            $form = $this->createForm(FacturationType::class, $facturation);
            $form->handleRequest($request);

            // Calculate the total price
            $totalPrice = $prixService->getSommeFacture($facturation);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($facturation);
                $entityManager->flush();

                // Dispatch the notification event
                $event = new SendNotificationEvent($facturation);
                $dispatcher->dispatch($event, SendNotificationEvent::NAME);

                $this->notifier->success(
                    'Merci pour la confirmation de votre paiement. Un SMS a été envoyé au client.'
                );

                // Clear the session
                $this->bookingService->clearSession();

                return $this->redirectToRoute('appartement_front', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('front/facturations.html.twig', [
                'facturation' => $facturation,
                'form' => $form,
                'reservation' => 'booking',
                'TotalPrix' => $totalPrice,
            ]);
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('appartement_front');
        }
    }

}
