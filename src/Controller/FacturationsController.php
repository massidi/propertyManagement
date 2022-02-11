<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Facturation;
use App\Event\SendNotificationEvent;
use App\Form\FacturationType;
use App\Repository\BookingRepository;
use App\Repository\FacturationRepository;
use App\Service\FacturationService;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\TexterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;


/**
 * @Route("/facturations")
 */
class FacturationsController extends AbstractController
{
    /**
     * @var FlashyNotifier
     */
    private $notifier;

    public function __construct(FlashyNotifier $notifier)
    {
        $this->notifier = $notifier;
    }

    /**
     * @Route("/liste-facturation", name="liste_facturation", methods={"GET"})
     * @param FacturationRepository $facturationRepository
     * @return Response
     */
    public function index(FacturationRepository $facturationRepository): Response
    {
        //return all the invoices

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
     * @param EventDispatcherInterface $dispatcher
     * @param TexterInterface $texter
     * @return Response
     */
    public function facture(Request $request, EntityManagerInterface $entityManager, RequestStack $requestStack, BookingRepository $bookingRepository,EventDispatcherInterface $dispatcher): Response
    {


        $session = $requestStack->getSession();
        $filters = $session->get('booking_new', []);


        $facturation = new Facturation();
        $form = $this->createForm(FacturationType::class, $facturation);
        $form->handleRequest($request);

        $facturation->setCreatedAd(new \DateTime('today'));

        $booking = $bookingRepository->find($filters->getid());


        $facturation->setBooking($booking);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($facturation);
            $entityManager->flush();


            //send a sms to the client after the payment is confirmed by using the event service

            $even=new SendNotificationEvent($facturation);
            $dispatcher->dispatch($even,SendNotificationEvent::NAME);

            $this->notifier->success('Merci pour la confirmation de votre payment une SMS vient d\'etre envoyer au cleint');



            return $this->redirectToRoute('liste_facturation', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('facturations/new.html.twig', [
            'facturation' => $facturation,
            'form' => $form,
            'reservation' => "booking"
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
     * @Route("/{id}/facturations_edit", name="facturations_edit", methods={"GET", "POST"})
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

            return $this->redirectToRoute('liste_facturation', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('facturations/edit.html.twig', [
            'facturation' => $facturation,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/facturations_delete/{id}", name="facturations_delete", methods={"POST"})
     * @param Request $request
     * @param Facturation $facturation
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function delete(Request $request, Facturation $facturation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $facturation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($facturation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('liste_facturation', [], Response::HTTP_SEE_OTHER);
    }
}
