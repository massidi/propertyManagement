<?php

namespace App\Controller;

use App\Repository\AppartementRepository;
use App\Repository\BookingRepository;
use App\Service\FacturationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncode;

class HomeController extends AbstractController
{
    /**
     * @Route("/admin", name="homepage")
     * @param AppartementRepository $appartementRepository
     * @param FacturationService $facturationService
     * @return Response
     */
    public function index(AppartementRepository  $appartementRepository,FacturationService  $facturationService): Response
    {


        $booked=$facturationService->factureData();


        $response = json_encode($booked);


        return $this->render('home/index.html.twig', [
            'nbrAppartement' => $appartementRepository->findAll(),
            'calendar'=>$response,
            'data'=>$booked

        ]);
    }

//    /**
//     * @param BookingRepository $bookingRepository
//     * @return Response
//     */
//    public function getCurrentBooking(BookingRepository $bookingRepository): Response
//    {
//        $booked=[];
//
//        //here getting the current reservation
//
//        foreach ($bookingRepository->getCurrentBooking() as $event)
//        {
//            $booked[]=[
//                'id'=>$event->getId(),
//                'start'=>$event->getCheckInAt(),
//                'end'=>$event->getCheckOutAt(),
//                'title'=>$event->getClients()[0]->getNom(),
//                'borderColor'=>"#ff0000",
//                'textColor'=>"#ff0000",
//                'backgroundColor'=>"#ff0000"
//
//
//
//
//            ];
//
//        }
//
////        dd($data);
////dd($bookingRepository->getCurrentBooking());
//        return  $data=$this->json($booked);
//    }

}
