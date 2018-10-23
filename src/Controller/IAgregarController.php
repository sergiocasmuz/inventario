<?php

namespace App\Controller;

use App\Entity\Articulos;
use App\Entity\ILineas;
use App\Entity\Familia;
use App\Entity\ICabecera;
use App\Entity\Marca;
use App\Entity\stock;
use App\Entity\NrosIdentificacion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityRepository;

class IAgregarController extends AbstractController
{

    /**
     * @Route("/ingreso/agregar/{orden}", name="ent")
     */

    public function agregar(Request $request, $orden)
    {

        $em = $this -> getDoctrine() -> getManager();
        $repository = $this->getDoctrine()->getRepository(Articulos::class);


        /********************** formulario numeros de articulo ************************************/

        $formNumero = $this -> createFormBuilder();
        $formNumero -> add('nroArticulo', TextType::class);
        $formNumero -> add('Buscar', SubmitType::class);

        $formNumero = $formNumero -> getForm();
        $formNumero -> handleRequest($request);

          if ($formNumero->isSubmitted() && $formNumero->isValid() ) {

            $rta = $formNumero -> getData();
            $nro = $rta["nroArticulo"];
            $nrosIdentificacion = $em -> getRepository(NrosIdentificacion::class) -> findByNroArticulo($nro); ////busco el "id" del articulo

            if(empty($nrosIdentificacion)){ $listaArticulos = $repository->findAll();}////////si no encuentro articulos
            else{
              $idArt = $nrosIdentificacion[0]->getIdArticulo();
              $listaArticulos = $repository -> findById($idArt); ///recupero los datos del articulo
              }




          }
          else{  $listaArticulos = $repository->findAll();}


        /* *************** FORMULARIO NUEVA ORDEN (LINEAS)******************** */


        $icabe = $em -> getRepository(ICabecera::class) -> find($orden);

        if (empty($icabe)){ return $this->redirect("/ingreso/articulos/{$orden}/");}

        if($icabe->getEstado() == 2 ){$act = true; }else{ $act =false;}

        $formularioIngreso = $this->createFormBuilder();
        $articulosTotales = count($listaArticulos);


        for($i=0; $i < $articulosTotales; $i++) {

          //controlo la cantidad
          $ArticuloEnLineas = $em -> getRepository(ILineas::class) -> findLineas($listaArticulos[$i]->getId(),$orden);

            if( empty($ArticuloEnLineas)   ){  $rta = 0; }
            else{  $rta = $ArticuloEnLineas[0]->getCantidad();}

            $formularioIngreso->add('idArticulo'.$i, HiddenType::class,
                array('attr' => array('value' => $listaArticulos[$i]->getId() )));

            $formularioIngreso->add('cantidad'.$i, IntegerType::class,
                array('attr' => array('value' => $rta, 'min' => 0) ) );

            $formularioIngreso->add('articulo'.$i, HiddenType::class,
                array('attr' => array('value' => $listaArticulos[$i]->getArticulo() )));

            $formularioIngreso->add('marca'.$i, HiddenType::class,
                array('attr' => array('value' => $listaArticulos[$i]->getMarca() )));

            $formularioIngreso->add('modelo'.$i, HiddenType::class,
                array('attr' => array('value' => $listaArticulos[$i]->getModelo() )));

            $formularioIngreso->add('familia'.$i, HiddenType::class,
                array('attr' => array('value' => $listaArticulos[$i]->getFamilia() )));

        }

        $formularioIngreso->add('save', SubmitType::class, array('label' => 'Agregar a la orden', 'attr' => array('disabled' => $act) ));
        $formularioIngreso = $formularioIngreso->getForm();

        $formularioIngreso = $formularioIngreso -> handleRequest($request);

        /* *************** RESPUESTA DE "NUEVA ORDEN (LINEAS)" ******************* */

        if ($formularioIngreso->isSubmitted() && $formularioIngreso->isValid() && $act == false) {

            $respuesta = $formularioIngreso->getData();
            $arRep = $em -> getRepository(Articulos::class) -> findAll();
            $cantArt = count($arRep);

            for($f=0; $f < $cantArt; $f++){

              $ilRep = $em -> getRepository(ILineas::class);

                $iLineas = new ILineas();



                $iLineas->setOrden($orden);
                $iLineas->setIdArticulo($respuesta["idArticulo".$f]);

                $iLineas->setCantidad($respuesta["cantidad".$f]);
                $iLineas->setArticulo($respuesta["articulo".$f]);
                $iLineas->setMarca($respuesta["marca".$f]);
                $iLineas->setModelo($respuesta["modelo".$f]);
                $iLineas->setFamilia($respuesta["familia".$f]);

                $idArt = $respuesta["idArticulo".$f];

                $query = $em -> createQuery("SELECT u FROM App\Entity\ILineas u WHERE u.orden = '$orden' and u.idArticulo= '$idArt' ");

                $rtaDQL = $query->getResult();

                $existencia = count($rtaDQL);

                if($existencia == 0){

                        if($respuesta["cantidad".$f] != 0){

                            $em->persist($iLineas);
                            $em->flush();
                            return $this->redirect("/ingreso/articulos/{$orden}");
                        }
                }
                else{
                        if($respuesta["cantidad".$f] != 0){

                            $ilRep2 = $em -> getRepository(ILineas::class)->find($rtaDQL[0] -> getId());
                            $ilRep2 -> setCantidad($respuesta["cantidad".$f]);
                            $em->flush();
                            return $this->redirect("/ingreso/articulos/{$orden}");
                        }
                }


            }




        }


        /* ************ FORMULARIO CONFIRMAR ORDEN ******************************* */

        $formularioOrden = $this -> createFormBuilder();

        $formularioOrden -> add('nombreForm', HiddenType::class, array( 'attr' => array('value' => 'formularioOrden' ) ) );
        $formularioOrden -> add('envOrden', SubmitType::class, array( 'label' => 'Terminar orden', 'attr' => array('class' => 'btn btn-primary') ) );

        $formularioOrden = $formularioOrden -> getForm();
        $formularioOrden -> handleRequest($request);


        /* *************** RESPUESTA DE FORMULARIO CONFIRMAR ORDEN *************** */

        if ($formularioOrden->isSubmitted() && $formularioOrden->isValid() && $act == false ) {


            $emLines = $this->getDoctrine() -> getManager();

            $iCabe = $emLines -> getRepository(ICabecera::class)->find($orden);
            $iCabe -> setEstado(1);
            $emLines->flush();

            $ilines = $emLines -> getRepository(ILineas::class)->findByOrden($orden);
            $emStock = $this -> getDoctrine() -> getManager();
            return $this->redirect("/ordenes");
        }

        $cabe = $em -> getRepository(ICabecera::class)->find($orden);
        $lineas = $this->getDoctrine()->getRepository(ILineas::class);
        $lineas = $lineas->findByOrden($orden);

        return $this->render('ingreso/agregar.html.twig', [
            'formularioIngreso' => $formularioIngreso->createView(),
            'formularioOrden' => $formularioOrden -> createView(),
            'listaArticulo' => $listaArticulos,
            'lineas' => $lineas,
            'orden' => $orden,
            'cabecera' => $cabe,
            'formNumero' => $formNumero -> createView(),

                 ]);

    }

}
