<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VérificationChambresController extends AbstractController
{
    /**
     * @Route("/v/rification/chambres", name="v_rification_chambres")
     */
    public function index(): Response
    {
        return $this->render('v�rification_chambres/filter.html.twig', [
            'controller_name' => 'VérificationChambresController',
        ]);
    }
}
