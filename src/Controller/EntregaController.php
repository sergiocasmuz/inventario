<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EntregaController extends AbstractController
{
    /**
     * @Route("/egreso", name="entrega")
     */
    public function index()
    {
        return $this->render('entrega/index.html.twig', [
            'controller_name' => 'EntregaController',
        ]);
    }
}
