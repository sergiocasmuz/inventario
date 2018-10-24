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


      echo  '<img src="barcode.php?text=0123456789&size=40&codetype=Code39&print=true" />';

    $dep = "hola";

        return $this->render('prueba/index.html.twig', [
            'dep' => $dep,
        ]);
    }
}
