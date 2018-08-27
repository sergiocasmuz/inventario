<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IngresoController extends AbstractController
{
    /**
     * @Route("/ingreso", name="ingreso")
     */
    public function index()
    {
        return $this->render('ingreso/index.html.twig', [
            'controller_name' => 'IngresoController',
        ]);
    }
}
