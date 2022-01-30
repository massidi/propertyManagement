<?php

namespace App\Controller;

use App\Repository\BookingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncode;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="homepage")
     */
    public function index(BookingRepository $bookingRepository): Response
    {
        $booked=[];

        //here getting the current reservation

//        dd($bookingRepository->getCurrentBooking());

        foreach ($bookingRepository->getCurrentBooking() as $event)
        {
            $booked[]=[
                'id'=>$event->getId(),
                'start'=>$event->getCheckInAt()->format('Y-m-d H:i:s'),
                'end'=>$event->getCheckOutAt()->format('Y-m-d H:i:s'),
                'title'=>$event->getClients()[0]->getNom(),
                'borderColor'=>"#00c853",
                'textColor'=>"#171a1d",
                'backgroundColor'=>"#00c853",
                'description'=>$event->getComment()

            ];

        }

        $response = json_encode($booked);





        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'calendar'=>$response
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
