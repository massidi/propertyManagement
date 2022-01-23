<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FacturationController extends AbstractController
{
    /**
     * @Route("/facturation", name="facturation")
     */
    public function index(): Response
    {
        return $this->render('facturation/index.html.twig', [
            'controller_name' => 'FacturationController',
        ]);
    }

    /**
     * @Route("/detail-facture/{id}", name="facturation")
     */
    public function facturation(): Response
    {
        return $this->render('facturation/facture.html.twig', [
            'controller_name' => 'FacturationController',
        ]);
    }

}
