<?php

namespace App\Controller\Parametre;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParametreController extends AbstractController
{
    /**
     * @Route("/admin/parametre", name="parametre")
     */
    public function index(): Response
    {
        return $this->render('parametre/index.html.twig', [
            'controller_name' => 'ParametreController',
        ]);
    }
}
