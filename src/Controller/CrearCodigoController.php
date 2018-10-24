<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\NrosIdentificacion;

class CrearCodigoController extends AbstractController
{
    /**
     * @Route("/crear/codigo/{orden}", name="crear_codigo")
     */
    public function index($orden)
    {
      $em = $this -> getDoctrine() -> getManager();
      $num = "Brown".rand(1000, 9999);

      function crearCod($num){

        $nrosIdentificacion = $em -> getRepositoty(nrosIdentificacion) -> findByNroArticulo($num);
        if(count($nrosIdentificacion) > 0){

          $num = "Brown".rand(10000, 99999);
          crearCod($num);
        }
          return $num;
        }

        $a = new nrosIdentificacion();

        $a -> setIdArticulo($orden);
        $a -> setNroArticulo($num);
        $a -> setNroUnico(0);

        $em -> persist($a);
        $em -> flush();



          return $this->redirect("/gestionar/codigos/$orden");


    }
}
