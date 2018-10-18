<?php

namespace App\Controller;

use App\Util\api;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PruebaController extends AbstractController
{
    /**
     * @Route("/prueba", name="prueba")
     */
    public function index()
    {
        $dep = api::get('dependencias');

        return $this->render('prueba/index.html.twig', [
            'dep' => $dep,
        ]);
    }
}
