<?php


namespace App\Service;


use App\Entity\Facturation;

class PrixService
{

    /**
     * @param $facturation
     * @return float|int
     */
    public  function  getSommeFacture($facturation)
    {
        $facturation= new  Facturation();
        //recuperer la date de l'entrée de client et  de sortie
        $later = $facturation->getBooking()->getCheckInAt();

        $earlier = $facturation->getBooking()->getCheckOutAt();

        //lz nombre de jours entre date d'entré et de sortie

        $abs_diff = $later->diff($earlier)->format("%a"); //3

        //calculer la sommes total entre le nombre des jours multiplier par le prix de l'appartement
        $TotalPrix=($abs_diff)*$facturation->getBooking()->getAppartement()->getNbrDeChambre();

        return $TotalPrix;

    }

}