<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\ELineas;
use App\Entity\ECabecera;

class ImprimirController extends AbstractController
{
    /**
     * @Route("/imprimir/{orden}", name="imprimir")
     */
    public function index($orden)
    {

      $em = $this -> getDoctrine() -> getManager();

      $ECabecera = $em -> getRepository(ECabecera::class) -> find( $orden );
      $ELineas = $em -> getRepository(ELineas::class) -> findByOrden( $orden );


        return $this->render('imprimir/notaEntrega.html.twig', [
            'cabecera' => $ECabecera,
            'lineas' => $ELineas,
        ]);
    }
}
