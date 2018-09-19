<?php

namespace App\Controller;

use App\Entity\Articulos;
use App\Entity\ECabecera;
use App\Entity\ELineas;
use App\Entity\Familia;
use App\Entity\ICabecera;
use App\Entity\ILineas;
use App\Entity\Marca;
use App\Entity\stock;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IngresoController extends AbstractController
{

    /**
     * @Route("/ordenIngreso", name="entrega3")
     */
    public function index(Request $request)
    {

        /* *********************************************************************** */
        /* *************** FORMULARIO NUEVA ORDEN (ENCABEZADO) ******************* */
        /* *********************************************************************** */

        $formularioCabecera = $this->createFormBuilder();
        $formularioCabecera ->add('nombreForm', HiddenType::class,array('attr' => array('value' => 'editarCabecera')));
        $formularioCabecera ->add('fecha', DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd','attr' => array("value" => date("Y-m-d") )));
        $formularioCabecera ->add('proveedor', TextType::class);
        $formularioCabecera ->add('receptor', TextType::class);
        $formularioCabecera ->add('remito', TextType::class);
        $formularioCabecera ->add('suministro', TextType::class);

        $formularioCabecera ->add('save', SubmitType::class, array('label' => 'Siguiente'));
        $formularioCabecera = $formularioCabecera ->getForm();

        $formularioCabecera->handleRequest($request);


        /* *********************************************************************** */
        /* **************** RESPUESTA NUEVA ORDEN (ENCABEZADO) ******************* */
        /* *********************************************************************** */

        if ($formularioCabecera->isSubmitted() && $formularioCabecera->isValid()) {

            $rta = $formularioCabecera ->getData();
            $nombreForm = $rta["nombreForm"];
            $Cabecera = new ICabecera();

            if($nombreForm == "editarCabecera") {


                $Cabecera -> setFecha($rta["fecha"]);
                $Cabecera -> setProveedor($rta["proveedor"]);
                $Cabecera -> setReceptor($rta["receptor"]);
                $Cabecera -> setRemito($rta["remito"]);
                $Cabecera -> setSuministro($rta["suministro"]);
                $Cabecera -> seteSTADO(0);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($Cabecera);
                $entityManager->flush();

                ///////el nro de orden corresponde con el id de la cabecera
                $orden = $Cabecera->getId();

                 return $this->redirect("/ingr_linea/{$orden}/agregar/");
            }
        }

        return $this->render('ingreso/ingr_cabecera.html.twig',
            ['formularioCabecera' => $formularioCabecera->createView()]);
    }



    /**
     * @Route("/ingr_linea/{orden}/{activar}", name="entrega")
     */

    public function linea(Request $request, $orden, $activar)
    {


        $repository = $this->getDoctrine()->getRepository(Articulos::class);
        $listaArticulos = $repository->findAll();


        /* *********************************************************************** */
        /* *************** FORMULARIO NUEVA ORDEN (LINEAS)****************************** */
        /* *********************************************************************** */
        $formularioIngreso = $this->createFormBuilder();

       $articulosTotales = count($listaArticulos);


        for($i=0; $i < $articulosTotales; $i++) {


            $formularioIngreso->add('idArticulo'.$i, HiddenType::class,
                array('attr' => array('value' => $listaArticulos[$i]->getId() )));

            $formularioIngreso->add('cantidad'.$i, TextType::class);

            $formularioIngreso->add('articulo'.$i, HiddenType::class,
                array('attr' => array('value' => $listaArticulos[$i]->getArticulo() )));

            $formularioIngreso->add('marca'.$i, HiddenType::class,
                array('attr' => array('value' => $listaArticulos[$i]->getMarca() )));

            $formularioIngreso->add('modelo'.$i, HiddenType::class,
                array('attr' => array('value' => $listaArticulos[$i]->getModelo() )));

            $formularioIngreso->add('familia'.$i, HiddenType::class,
                array('attr' => array('value' => $listaArticulos[$i]->getFamilia() )));

        }

        $formularioIngreso->add('save', SubmitType::class, array('label' => 'Agregar Lineas' ));

        $formularioIngreso = $formularioIngreso->getForm();

        $formularioIngreso->handleRequest($request);

        /* *********************************************************************** */
        /* *************** RESPUESTA DE "NUEVA ORDEN (LINEAS)" ************************* */
        /* *********************************************************************** */

        if ($formularioIngreso->isSubmitted() && $formularioIngreso->isValid()) {

            $respuesta = $formularioIngreso->getData();

            $em = $this->getDoctrine()->getManager();

            $ar = $this->getDoctrine()
            -> getRepository(Articulos::class)
            -> findAll();

            $cantArt = count($ar);

            for($f=0; $f < $cantArt; $f++){

                $idArt =  $respuesta["idArticulo".$f];
                $cantidad =  $respuesta["cantidad".$f];
                $marca =  $respuesta["marca".$f];
                $modelo =  $respuesta["modelo".$f];
                $articulo =  $respuesta["articulo".$f];
                $familia =  $respuesta["familia".$f];


                $iLineas = new ILineas();

                $iLineas->setOrden($orden);
                $iLineas->setIdArticulo($idArt);
                $iLineas->setCantidad($cantidad);
                $iLineas->setArticulo($articulo);
                $iLineas->setMarca($marca);
                $iLineas->setModelo($modelo);
                $iLineas->setFamilia($familia);

                
                $il = $this -> getDoctrine() -> getRepository(ILineas::class);

                $query = $em->createQuery("SELECT u FROM App\Entity\ILineas u WHERE u.orden = '$orden' and u.idArticulo= '$idArt' ");

                $rtaDQL = $query->getResult();

                $existencia = count($rtaDQL); 

                if($existencia == 0){

                        if($cantidad != 0){

                            $em->persist($iLineas);
                            $em->flush();
                        }
                }
                else{
                             
                        $canDQL = $rtaDQL[0]->getCantidad();
                        $idDQL = $rtaDQL[0]->getId();
                     
                        $suma = $cantidad + $canDQL;

                        if($cantidad != 0){
                            
                            $il ->find($idDQL)->setCantidad($suma);
                            $em->flush();
                        }
                               

                }      
                

            }


            return $this->redirect("/ingr_linea/{$orden}/agregar/");

        }


        /* *********************************************************************** */
        /* *************** FORMULARIO DE CABECERA ******************************** */
        /* *********************************************************************** */

        $eCab = $this -> getDoctrine() -> getManager();
        $cab = $eCab -> getRepository(ICabecera::class) -> find($orden);

        $editarCabecera = $this->createFormBuilder();

        $editarCabecera ->add('nombreForm', HiddenType::class,array('attr' => array('value' => 'editarCabecera')));
        $editarCabecera ->add('fecha', DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd','attr' => array("value" => date("Y-m-d") )));
        $editarCabecera ->add('proveedor', TextType::class, array('attr' => array('value'=> $cab->getProveedor())) );
        $editarCabecera ->add('receptor', TextType::class, array('attr' => array('value'=> $cab->getReceptor())));
        $editarCabecera ->add('remito', TextType::class, array('attr' => array('value'=> $cab->getRemito())));
        $editarCabecera ->add('suministro', TextType::class, array('attr' => array('value'=> $cab->getSuministro())));

        $editarCabecera ->add('save', SubmitType::class, array('label' => 'Siguiente'));
        $editarCabecera = $editarCabecera ->getForm();

        $editarCabecera->handleRequest($request);

        /* *********************************************************************** */
        /* *************** RESPUESTA DE "FORMULARIO DE CABECERA" ***************** */
        /* *********************************************************************** */


        if ( $editarCabecera->isSubmitted() && $editarCabecera->isValid() ) {

            $rta = $editarCabecera->getData();



                $eManager = $this->getDoctrine()->getManager();
                $ICabecera = $eManager->getRepository(ICabecera::class)->find($orden);

                $ICabecera -> setFecha($rta["fecha"]);
                $ICabecera -> setProveedor($rta["proveedor"]);
                $ICabecera -> setReceptor($rta["receptor"]);
                $ICabecera -> setRemito($rta["remito"]);
                $ICabecera -> setSuministro($rta["suministro"]);

                $eManager->persist($ICabecera);
                $eManager->flush();

                ///////el nro de orden corresponde con el id de la cabecera
                $orden = $ICabecera->getId();


                return $this->redirect("/ingr_linea/{$orden}/cabecera");



        }


        $repCabecera = $this->getDoctrine()->getRepository(ICabecera::class);
        $cabe = $repCabecera->find($orden);


        /* *********************************************************************** */
        /* *************** FORMULARIO QUITAR LINEAS ****************************** */
        /* *********************************************************************** */

        $lineas = $this->getDoctrine()->getRepository(ILineas::class);
        $lineas = $lineas->findBy( ['orden' => $orden] );

        $formPedido = $this->createFormBuilder();

        foreach ($lineas as $a ) {
            $formPedido->add($a->getId(), SubmitType::class, array('label' => 'Eliminar línea'));
        }

        $formPedido = $formPedido->getForm();

        $formPedido->handleRequest($request);


        /* *********************************************************************** */
        /* *************** RESPUESTA DE "QUITAR LINEAS" ************************** */
        /* *********************************************************************** */

        if ($formPedido->isSubmitted() && $formPedido->isValid()) {

            $rta = $formPedido -> getData();

            $idBorrar=$formPedido->getClickedButton()->getName();

            $entityManager = $this->getDoctrine()->getManager();
            $iLineas = $entityManager->getRepository(ILineas::class)->find($idBorrar);

            $entityManager->remove($iLineas);
            $entityManager->flush();

            $activar = "quitar";
            return $this->redirect("/ingr_linea/{$orden}/quitar");
        }


        /* *********************************************************************** */
        /* ************ FORMULARIO CONFIRMAR ORDEN ******************************* */
        /* *********************************************************************** */

        $formularioOrden = $this -> createFormBuilder();

        $formularioOrden -> add('nombreForm', HiddenType::class, array( 'attr' => array('value' => 'formularioOrden' ) ) );
        $formularioOrden -> add('envOrden', SubmitType::class, array( 'label' => 'Terminar orden', 'attr' => array('class' => 'btn btn-primary') ) );

        $formularioOrden = $formularioOrden -> getForm();
        $formularioOrden -> handleRequest($request);


        /* *********************************************************************** */
        /* *************** RESPUESTA DE FORMULARIO CONFIRMAR ORDEN ****************** */
        /* *********************************************************************** */

        if ($formularioOrden->isSubmitted() && $formularioOrden->isValid() ) {


            $emLines = $this->getDoctrine() -> getManager();

            $iCabe = $emLines -> getRepository(ICabecera::class)->find($orden);
            $iCabe -> setEstado(1);
            $emLines->flush();


            $ilines = $emLines -> getRepository(ILineas::class)->findByOrden($orden);

            $emStock = $this -> getDoctrine() -> getManager();


           foreach ($ilines as $line){

               $stock = $emStock -> getRepository(stock::class) -> findByIdArticulo($line->getIdArticulo());

               echo $stock[0]->getId()."<br>";

               $suma = $stock[0]->getCantidad() + $line -> getCantidad();


               $stock[0] -> setCantidad($suma);
               $emStock->flush();
           }

            return $this->redirect("/");
        }



        /* *********************************************************************** */
        /* *************** FORMULARIO NUEVO ARTICULO****************************** */
        /* *********************************************************************** */

        $articulos = new Articulos();

        $formulario = $this->createFormBuilder($articulos);


        $em = $this -> getDoctrine() -> getManager();

        $familia = $em -> getRepository(Familia::class) -> findBy(array(),array('familia'=>'ASC'));

        $marca = $em -> getRepository(Marca::class) -> findBy(array(),array('marca'=>'ASC'));



        foreach ($marca as $item0) {
                                    $marcaList[""] = null;
                                    $marcaList[$item0->getMarca()] = $item0->getMarca();
                                    }



        foreach ($familia as $item1) {
                                        $familiaList[""] = null;
                                        $familiaList[$item1->getFamilia()] = $item1->getFamilia() ;

                                    }





        $formulario->add('familia',ChoiceType::class, array( 'choices'  => array('Seleccioná una familia' =>$familiaList)));
        $formulario->add('articulo',TextType::class);
        $formulario->add('marca',ChoiceType::class, array( 'choices'  => array('Seleccioná una marca' =>$marcaList)));
        $formulario->add('modelo',TextType::class);
        $formulario->add('detalle',TextType::class);
        $formulario->add('save', SubmitType::class, array('label' => 'Guardar'));
        $formulario = $formulario->getForm();

        $formulario = $formulario->handleRequest($request);

        if ($formulario->isSubmitted() && $formulario->isValid()) {


            $em = $this -> getDoctrine() -> getManager();

            /* *******INGRESAR ARTICULO************ */

            $tablaArt = $em ->getRepository(Articulos::class);
            $art = $formulario -> getData();


            $em->persist($art);
            $em->flush();

            $idArt = $art->getId();
            $familia = $art-> getFamilia();
            $articulo = $art-> getArticulo();
            $marca = $art-> getMarca();
            $modelo = $art-> getModelo();
            $detalle = $art-> getDetalle();

            echo $art-> getFamilia();
            /* *******INGRESAR STOCK************ */

            $tablaArt = $em ->getRepository(stock::class);

            $stock = new stock();

            $stock -> setFamilia($familia);
            $stock -> setArticulo($articulo);
            $stock -> setMarca($marca);
            $stock -> setModelo($modelo);
            $stock -> setDetalle($detalle);
            $stock -> setCantidad(0);
            $stock -> setIdArticulo($idArt);

            $em->persist($stock);
            $em->flush();

            return $this->redirect("/ingr_linea/{$orden}/agregar");
        }


        $emCabe = $this -> getDoctrine() -> getManager();
        $cabe = $emCabe -> getRepository(ICabecera::class)->find($orden);





        return $this->render('ingreso/ingr_linea.html.twig', [
            'formularioIngreso' => $formularioIngreso->createView(),
            'editarCabecera' => $editarCabecera->createView(),
            'formPedido' => $formPedido->createView(),
            'formularioArticuloN' => $formulario->createView(),
            'formularioOrden' => $formularioOrden -> createView(),
            'listaArticulo' => $listaArticulos,
            'lineas' => $lineas,
            'activar' => $activar,
            'orden' => $orden,
            'cabecera' => $cabe,
        
                 ]);


    }








}
