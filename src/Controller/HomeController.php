<?php

namespace App\Controller;

use App\Entity\stock;
use App\Entity\ECabecera;
use App\Entity\ELineas;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;


use Doctrine\DBAL\Driver\Connection;

class HomeController extends AbstractController
{
    /**
     * @Route("/estadisticas", name="home")
     */
    public function index(Request $request, Connection $connection)
    {

        $em = $this -> getDoctrine() -> getManager();



        $formulario = $this -> createFormBuilder()
        -> add("filtro", ChoiceType::class, array('choices' => array('----' =>
                                                                              array('Dependencias' => 'destino',
                                                                                    'Artículos' => 'articulo',
                                                                                    'Familias' => 'familia') ) ) )

        -> getForm()
        -> handleRequest($request);

        $rta = array();

        $filtro = "";
        if ($formulario->isSubmitted() && $formulario->isValid()) {

          $resp = $formulario -> getData();

          switch ($resp["filtro"]) {
            case 'destino':
                      $sql = "SELECT destino as fil, sum(cantidad) as total from ELineas li
                     left join ECabecera ca on li.orden = ca.id group by destino";
              break;

            case 'articulo':
                    $sql = "SELECT articulo as fil, sum(cantidad) as total from ELineas li
                     left join ECabecera ca on li.orden = ca.id group by articulo";
              break;

            case 'familia':
                    $sql = "SELECT familia as fil, sum(cantidad) as total from ELineas li
                     left join ECabecera ca on li.orden = ca.id group by familia";
              break;

          }

          $filtro = $resp["filtro"];

          $stm = $connection -> prepare($sql);

          $stm -> execute();
          $rta = $stm -> fetchAll();
        }

        return $this->render('home/index.html.twig', [
          'filtro' => $filtro,
          'array' => $rta,
          'formulario' => $formulario -> createView()
        ]);
    }
}
