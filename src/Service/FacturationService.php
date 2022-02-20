<?php


namespace App\Service;


use App\Repository\FacturationRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FacturationService
{
    /**
     * @var FacturationRepository
     */
    private $facturationRepository;
    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * FacturationService constructor.
     * @param FacturationRepository $facturationRepository
     */
    public function __construct(FacturationRepository  $facturationRepository,UrlGeneratorInterface $router)
    {
        $this->facturationRepository = $facturationRepository;
        $this->router = $router;
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
               'url'=>$this->router->generate('facturations_detail', ['id' => $value->getId()])




           ];

       }
//       dd($data);

     return $data;
    }


}