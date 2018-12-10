<?php

namespace App\Controller;

use App\Entity\Articulos;
use App\Entity\ECabecera;
use App\Entity\ICabecera;
use App\Entity\ELineas;
use App\Entity\ILineas;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityRepository;

class verOrdenController extends AbstractController
{
    /**
     * @Route("/ver/entrega/{orden}", name="orden_e")
     */

    public function linea($orden)
    {
              $validacion = 0;
              $em = $this -> getDoctrine() -> getManager();
              $ecabe = $em -> getRepository(ECabecera::class) -> find($orden);
              $lineas = $em -> getRepository(ELineas::class) -> findByOrden($orden);


      return $this->render('e_articulos/verEntrega.html.twig', [
          'orden' => $orden,
          'cabecera' => $ecabe,
          'lineas' => $lineas

               ]);

    }


    /**
     * @Route("/ver/ingreso/{orden}", name="orden_i")
     */

    public function linea2($orden)
    {
              $validacion = 0;
              $em = $this -> getDoctrine() -> getManager();
              $icabe = $em -> getRepository(ICabecera::class) -> find($orden);
              $lineas = $em -> getRepository(ILineas::class) -> findByOrden($orden);


      return $this->render('e_articulos/verIngreso.html.twig', [
          'orden' => $orden,
          'cabecera' => $icabe,
          'lineas' => $lineas

               ]);

    }

}
