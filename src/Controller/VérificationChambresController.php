<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class V√©rificationChambresController extends AbstractController
{
    /**
     * @Route("/v/rification/chambres", name="v_rification_chambres")
     */
    public function index(): Response
    {
        return $this->render('v„©rification_chambres/index.html.twig', [
            'controller_name' => 'V√©rificationChambresController',
        ]);
    }
}
