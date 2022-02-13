<?php


namespace App\Service;


use App\Repository\FacturationRepository;

class FacturationService
{
    /**
     * @var FacturationRepository
     */
    private $facturationRepository;

    /**
     * FacturationService constructor.
     * @param FacturationRepository $facturationRepository
     */
    public function __construct(FacturationRepository  $facturationRepository)
    {
        $this->facturationRepository = $facturationRepository;
    }

    public function factureData()
    {
        $color="";
        $status="";
        $data=[];
       $invoice= $this->facturationRepository->findAll();

       foreach ($invoice as $value)
       {


           if (new \DateTime('today') >= $value->getPaymentAt()) {
               $color = "#00c853";
               $status="  completé";
           } else {
               $color = "#00bcd4";
               $status="En attente";
           }

           $data[]=[

               'id'=>$value->getBooking()->getId(),
               'start'=>$value->getBooking()->getCheckInAt()->format('Y-m-d H:i:s'),
               'end'=>$value->getBooking()->getCheckOutAt()->format('Y-m-d H:i:s'),
               'title'=>$value->getBooking()->getClients()[0]->getNom()." ".$status,
               'borderColor'=>$color,
               'textColor'=>"#171a1d",
               'backgroundColor'=>$color,
               'description'=>$value->getBooking()->getComment(),




           ];

       }
//       dd($data);

     return $data;
    }


}