<?php

namespace App\Controller;

use App\Entity\ICabecera;
use App\Entity\ILineas;
use App\Entity\stock;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;

class OrdenesController extends AbstractController
{
    /**
     * @Route("/ordenes", name="ordenes")
     */
    public function index(Request $request)
    {

        $ilineas = array();
        $em = $this -> getDoctrine() -> getManager();
        $icabecera = $em -> getRepository(ICabecera::class) -> findBy(array(),array('id'=>'DESC'));

        $formularioEstado = $this -> createFormBuilder();

        foreach ($icabecera as $a){



            $formularioEstado -> add('nombreForm', HiddenType::class, array('attr' => array("value" => 'estadoForm' )) );
            $formularioEstado -> add('id_'.$a->getId(), HiddenType::class, array('attr' => array("value" => $a->getId(),  )));

            switch ($a->getEstado()){

                case 0:
                    $formularioEstado -> add($a->getId(), SubmitType::class,
                        array("label" => 'Eliminar',
                            'attr' => array('class' => 'btnC btn-secondary' )) );
                    break;

                case 1:
                        $formularioEstado -> add($a->getId(), SubmitType::class,
                        array("label" => 'Agregar a stock',
                        'attr' => array('class' => 'btnC btn-primary' )) );

                    break;

                case 2:
                        $formularioEstado -> add($a->getId(), HiddenType::class,
                        array("label" => 'Imprimir nota',
                        'attr' => array('class' => 'btnC btn-info' )) );
                    break;

            }


            $ilineas = $em -> getRepository(ILineas::class) -> findByOrden($a->getId());

        }

        $formularioEstado = $formularioEstado->getForm();
        $formularioEstado->handleRequest($request);

        if ($formularioEstado->isSubmitted() && $formularioEstado->isValid() ) {



            $emLines = $this->getDoctrine() -> getManager();
            $orden = $formularioEstado -> getClickedButton() -> getName();
            $iCabe = $emLines -> getRepository(ICabecera::class)->find($orden);

            $estado = $iCabe->getEstado();




            switch ($estado){

                case 0:

                  $em = $this -> getDoctrine() -> getManager();
                  $ilines = $em -> getRepository(ILineas::class)->findByOrden($orden);
                  $iCabe = $em -> getRepository(ICabecera::class)->find($orden);

                  $em -> remove($iCabe);
                  $em -> flush();

                  foreach ($ilines as $line){



                      $em-> remove($line);
                      $em->flush();

                  }

                  return $this->redirect("ordenes");
                    break;

                case 1:
                    $iCabe -> setEstado(2);
                    $emLines->flush();


                    $emStock = $this -> getDoctrine() -> getManager();
                    $ilines = $emLines -> getRepository(ILineas::class)->findByOrden($orden);


                    foreach ($ilines as $line){

                        $stock = $emStock -> getRepository(stock::class) -> findByIdArticulo($line->getIdArticulo());
                        $suma = $stock[0]->getCantidad() + $line -> getCantidad();

                        $stock[0] -> setCantidad($suma);
                        $emStock->flush();

                    }

                    return $this->redirect("ordenes");

                    break;

                case 2:

                    break;
            }

        }

        return $this->render('ordenes/index.html.twig', [
            'ordenes' => $icabecera,
            'lineas' => $ilineas,
            'formularioEstado' => $formularioEstado -> createView()
        ]);
    }
}
