<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GestionErroresController extends AbstractController
{
    /**
     * @Route("/gestion/errores/{nroError}", name="gestion_errores")
     */
    public function index($nroError)
    {

      switch ($nroError) {
        case '1':
          $mensaje = "No se encontró la orden";
          $link = "/ordenEntrega";
          break;

        default:
          $mensaje = "Sin errores";
          break;
      }


        return $this->render('gestion_errores/index.html.twig', [
            'mensaje' => $mensaje,
            'link' => $link
        ]);
    }
}
