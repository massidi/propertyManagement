<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CheckOutCheckInController extends AbstractController
{
    /**
     * @Route("/check/out/check/in", name="check_out_check_in")
     */
    public function index(): Response
    {
        return $this->render('check_out_check_in/index.html.twig', [
            'controller_name' => 'CheckOutCheckInController',
        ]);
    }
}
