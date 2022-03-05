<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Facturation;
use App\Event\SendNotificationEvent;
use App\Form\FacturationType;
use App\Repository\BookingRepository;
use App\Repository\FacturationRepository;
use App\Service\GeneratePdfService;
use App\Service\PrixService;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;


/**
 * @Route("/admin/facturations")
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

        //recuperer la date de l'entrée de client et  de sortie
        $later = $facturation->getBooking()->getCheckInAt();

        $earlier = $facturation->getBooking()->getCheckOutAt();

        //le nombre de jours entre date d'entré et de sortie

        $abs_diff = $later->diff($earlier)->format("%a"); //3

        //calculer la sommes total entre le nombre des jours multiplier par le prix de l'appartement
        $TotalPrix=($abs_diff)*$facturation->getBooking()->getAppartement()->getPrice();

//        dd($facturation->getBooking());



        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($facturation);
            $entityManager->flush();


            //send a sms to the client after the payment is confirmed by using the event service

            $even=new SendNotificationEvent($facturation);
//            dd($even->getBooking()->getBooking()->getClients()[0]->getTelephone());
            $dispatcher->dispatch($even,SendNotificationEvent::NAME);

            $this->notifier->success('Merci pour la confirmation de votre payment une SMS vient d\'etre envoyer au cleint');



            return $this->redirectToRoute('liste_facturation', [], Response::HTTP_SEE_OTHER);
        }
//        else if ($facturation)
//        {
//            $booking = $bookingRepository->find($filters->getid());
//            $entityManager->remove($booking);
//            return $this->redirectToRoute('booking_index', [], Response::HTTP_SEE_OTHER);
//
//        }


        return $this->renderForm('facturations/new.html.twig', [
            'facturation' => $facturation,
            'form' => $form,
            'reservation' => "booking",
            'TotalPrix'=>$TotalPrix

        ]);
    }

    /**
     * @Route("/print_incoice/{id}", name="print_invoice", methods={"GET"})
     * @param Facturation $facturation
     * @param GeneratePdfService $pdfService
     * @return Response
     */
    public function invoicePrint(Facturation $facturation, GeneratePdfService  $pdfService): Response
    {
        $title=$facturation->getBooking()->getClients()[0]->getNom();
        $htm= $this->render('facturations/invoice-print.html.twig', [
            'facturation' => $facturation,
        ]);
        $pdfService->pdfAction($htm,$title);
        return $htm ;

    }


    /**
     * @Route("/detail-facture/{id}", name="facturations_detail", methods={"GET"})
     * @param Facturation $facturation
     * @return Response
     */
    public function show(Facturation $facturation): Response
    {
        //recuperer la date de l'entrée de client et  de sortie
        $later = $facturation->getBooking()->getCheckInAt();

        $earlier = $facturation->getBooking()->getCheckOutAt();

        //lz nombre de jours entre date d'entré et de sortie

        $abs_diff = $later->diff($earlier)->format("%a"); //3

        //calculer la sommes total entre le nombre des jours multiplier par le prix de l'appartement
        $TotalPrix=($abs_diff)*($facturation->getBooking()->getAppartement()->getPrice());

        return $this->render('facturations/show.html.twig', [
            'facturation' => $facturation,
            'TotalPrix'=>$TotalPrix
        ]);
    }



    /**
     * @Route("/facturations_edit/{id}", name="facturations_edit", methods={"GET", "POST"})
     * @param Request $request
     * @param Facturation $facturation
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function edit(Request $request, Facturation $facturation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FacturationType::class, $facturation);
        $form->handleRequest($request);

        //recuperer la date de l'entrée de client et  de sortie
        $later = $facturation->getBooking()->getCheckInAt();

        $earlier = $facturation->getBooking()->getCheckOutAt();

        //lz nombre de jours entre date d'entré et de sortie

        $abs_diff = $later->diff($earlier)->format("%a"); //3

        //calculer la sommes total entre le nombre des jours multiplier par le prix de l'appartement
        $TotalPrix=($abs_diff)*($facturation->getBooking()->getAppartement()->getPrice());
//        dd([$abs_diff,$TotalPrix,$facturation->getBooking()->getAppartement()->getNbrDeChambre()],[$later,$earlier]);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('liste_facturation', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('facturations/edit.html.twig', [
            'facturation' => $facturation,
            'form' => $form,
            'TotalPrix'=>$TotalPrix

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
//    /**
//     * @Route("/supprimer_delete/{id}", name="supprimer_delete", methods={"POST"})
//     * @param Request $request
//     * @param Facturation $facturation
//     * @param EntityManagerInterface $entityManager
//     * @return Response
//     */
//    public function supprimer(Request $request, Facturation $facturation, EntityManagerInterface $entityManager): Response
//    {
//
//
//        if ($this->isCsrfTokenValid('delete' . $facturation->getBooking()->getId(), $request->request->get('_token'))) {
//            $entityManager->remove($facturation);
//            $entityManager->flush();
//        }
//
//        return $this->redirectToRoute('liste_facturation', [], Response::HTTP_SEE_OTHER);
//    }
}
