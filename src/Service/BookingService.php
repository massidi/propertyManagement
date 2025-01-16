<?php

namespace App\Service;

use App\Entity\Booking;
use App\Entity\Appartement;
use App\Repository\AppartementRepository;
use Exception;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class BookingService
{
    private AppartementRepository $appartementRepository;
    private SessionInterface $session;

    public function __construct(AppartementRepository $appartementRepository, RequestStack $requestStack)
    {
        $this->appartementRepository = $appartementRepository;
        $this->session = $requestStack->getSession();
    }

    public function createBooking(
        Booking $booking,
        Appartement $appartement,
        \DateTimeInterface $checkInAt,
        \DateTimeInterface $checkOutAt
    ): bool {
        $booking->setAppartement($appartement);

        // Check apartment availability
        $roomAvailability = $this->appartementRepository->checkAppartementAvailability(
            $appartement->getId(),
            $checkInAt->format('Y-m-d H:i'),
            $checkOutAt->format('Y-m-d H:i')
        );

        if ($roomAvailability == "0") {
            // Store booking in session
            $this->session->set('booking_new', $booking);
            return true; // Booking is successful
        }

        // If booking fails, clean up the session
        $this->clearBookingSession();
        return false; // Room is already booked
    }

    public function clearBookingSession(): void
    {
        $this->session->remove('booking_new');
    }

    /**
     * @throws Exception
     */
    public function createBookingFromSession(): Booking
    {
        $filters = $this->session->get('booking_new');

        if (!$filters) {
            throw new Exception("Booking not found in session.");
        }

        $booking = new Booking();
        $booking->setCheckInAt($filters->getCheckInAt());
        $booking->setCheckOutAt($filters->getCheckOutAt());

        $appartement = $this->appartementRepository->find($filters->getAppartement()->getId());
        if (!$appartement) {
            throw new Exception("Appartement not found for the given ID.");
        }

        $booking->setAppartement($appartement);
        $booking->setComment($filters->getComment());

        foreach ($filters->getClients() as $client) {
            $booking->addClient($client);
        }

        return $booking;
    }
    public function clearSession(): void
    {
        $this->session->remove('booking_new');
    }
}
