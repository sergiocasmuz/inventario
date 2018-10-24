<?php

namespace App\Controller;

use App\Entity\ECabecera;
use App\Entity\ELineas;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;

class OrdenEntregaController extends AbstractController
{
    /**
     * @Route("/ordenEntrega", name="ordenEntrega")
     */
    public function index(Request $request)
    {

/********************funcionaes*******************/
function borrarOrden($orden,$em){

            $eL = $em -> getRepository(ELineas::class) -> findByOrden($orden);

            //////////////////////borrar articulos relacionados
            foreach ($eL as $e) {
                                $em->remove($e);
                                print_r($e);
                                }
                        $em -> flush();

                        $eCa = $em -> getRepository(ECabecera::class) -> find($orden);
                        $em -> remove($eCa);
                        $em -> flush();
                        }


/***********************fin de funciones*************************/

        $elineas = array();
        $em = $this -> getDoctrine() -> getManager();
        $ecabecera = $em -> getRepository(ECabecera::class) -> findBy(array(),array('id'=>'DESC'));

        $formularioEstado = $this -> createFormBuilder();

        foreach ($ecabecera as $a){

            $formularioEstado -> add('nombreForm', HiddenType::class, array('attr' => array("value" => 'estadoForm' )) );
            $formularioEstado -> add('id_'.$a->getId(), HiddenType::class, array('attr' => array("value" => $a->getId(),  )));

            switch ($a->getEstado()){

                case 0: ///////EN PROCESO
                    $formularioEstado -> add("btnA".$a->getId(), SubmitType::class,
                        array("label" => 'Eliminar',
                            'attr' => array('class' => 'btn-danger' )) );

                            $formularioEstado -> add("btnB".$a->getId(), HiddenType::class,
                            array("label" => 'Rechazar',
                            'attr' => array('class' => 'btn-light' )) );
                    break;

                case 1: ///////////PENDIENTE
                        $formularioEstado -> add("btnA".$a->getId(), SubmitType::class,
                        array("label" => 'Autorizar',
                        'attr' => array('class' => 'btn-info' )) );


                        $formularioEstado -> add("btnB".$a->getId(), SubmitType::class,
                        array("label" => 'Rechazar',
                        'attr' => array('class' => 'btn-secondary' )) );

                    break;

                case 2://///////////////////ACEPTADO
                            $formularioEstado -> add("btnA".$a->getId(), SubmitType::class,
                            array("label" => 'Iniciar entrega',
                            'attr' => array('class' => ' btn-info' )) );

                            $formularioEstado -> add("btnB".$a->getId(), HiddenType::class,
                            array("label" => 'Rechazado',
                            'attr' => array('class' => 'btn-primary' )) );
                    break;


                  case 3:////////////////////////rechazado
                            $formularioEstado -> add("btnA".$a->getId(), SubmitType::class,
                            array("label" => 'Eliminar',
                            'attr' => array('class' => ' btn-danger' )) );

                            $formularioEstado -> add("btnB".$a->getId(), HiddenType::class,
                            array("label" => 'Borrar',
                            'attr' => array('class' => ' btn-primary' )) );
                        break;


                    case 4://///// ////////////////en tránsito

                                $formularioEstado -> add("btnA".$a->getId(), SubmitType::class,
                                array("label" => 'Confirmar entrega',
                                'attr' => array('class' => ' btn-success' )) );

                                $formularioEstado -> add("btnB".$a->getId(), SubmitType::class,
                                array("label" => 'Eliminar',
                                'attr' => array('class' => ' btn-primary' )) );

                            break;


                      case 5:///////////////////////finalizado
                              $formularioEstado -> add("btnA".$a->getId(), HiddenType::class,
                              array("label" => 'Finalizado',
                              'attr' => array('class' => ' btn-info' )) );

                              $formularioEstado -> add("btnB".$a->getId(), HiddenType::class,
                              array("label" => 'Rechazado',
                              'attr' => array('class' => ' btn-primary' )) );
                          break;


            }


            $elineas = $em -> getRepository(ELineas::class) -> findByOrden($a->getId());

        }

        $formularioEstado = $formularioEstado->getForm();
        $formularioEstado->handleRequest($request);

        if ($formularioEstado->isSubmitted() && $formularioEstado->isValid() ) {

            $emLines = $this->getDoctrine() -> getManager();
            $cadena = $formularioEstado -> getClickedButton() -> getName();

            $exRegular = preg_match("/^btn[A][0-9]/", $cadena);  //////busco que btn de clikeó

            if($exRegular == 1){$bot="btnA";} else{$bot="btnB";} //////variable btn

            $orden = intval(preg_replace('/[^0-9]+/', '', $cadena));


            $eCabe = $emLines -> getRepository(ECabecera::class)->find($orden);

            $estado = $eCabe->getEstado();

            switch ($estado){

                case 0:

                  $em = $this -> getDoctrine() -> getManager();
                  $elines = $em -> getRepository(ELineas::class)->findByOrden($orden);
                  $eCabe = $em -> getRepository(ECabecera::class)->find($orden);

                  $em -> remove($eCabe);
                  $em -> flush();

                  foreach ($elines as $line){

                        $em-> remove($line);
                        $em->flush();
                  }

                  return $this->redirect("ordenEntrega");
                    break;

                case 1://////pendiente

                    if($bot == "btnA"){
                                    $eCabe -> setEstado(2);
                                    $emLines->flush();

                                    }

                      elseif($bot == "btnB"){
                                              $eCabe -> setEstado(3); ///////rechazado
                                              $emLines->flush();
                                            }
                    break;


                case 2:
                      $eCabe -> setEstado(4); ///////en tránsito
                      $emLines->flush();
                    break;


                case 3:
                          borrarOrden($orden,$em);
                      break;

                  case 4:
                        if($bot=="btnA"){

                                          return $this->redirect("/validar/orden/entrega/".$orden);

                                        }

                        elseif($bot=="btnB"){
                                          $eCabe -> setEstado(6); ///////en tránsito
                                          $emLines->flush();
                                        }
                        break;


                    case 6:
                          if($bot=="btnA"){
                                          $eCabe -> setEstado(5); ///////finalizar
                                          $emLines->flush();  }

                          elseif($bot=="btnB"){
                                                borrarOrden($orden,$em);
                                              }

                          break;
            }

            return $this->redirect("ordenEntrega");

        }




        return $this->render('ordenes/entrega.html.twig', [
            'ordenes' => $ecabecera,
            'formularioEstado' => $formularioEstado -> createView()
        ]);
    }
}
