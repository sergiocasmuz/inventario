<?php

namespace App\Controller;

use App\Entity\Articulos;
use App\Entity\ECabecera;
use App\Entity\ELineas;
use App\Entity\ICabecera;
use App\Entity\ILineas;
use App\Entity\stock;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

        $formularioCabecera = $this->createFormBuilder();
        $formularioCabecera ->add('nombreForm', HiddenType::class,array('attr' => array('value' => 'editarCabecera')));
        $formularioCabecera ->add('fecha', DateType::class,array('attr' => array('value' => date("Y-m-d"))));
        $formularioCabecera ->add('proveedor', TextType::class);
        $formularioCabecera ->add('save', SubmitType::class, array('label' => 'Siguiente'));
        $formularioCabecera = $formularioCabecera ->getForm();

        $formularioCabecera->handleRequest($request);

        if ($formularioCabecera->isSubmitted() && $formularioCabecera->isValid()) {

            $rta = $formularioCabecera ->getData();
            $nombreForm = $rta["nombreForm"];
            $Cabecera = new ICabecera();

            if($nombreForm == "editarCabecera") {

                echo "editarCabecera";

                $Cabecera -> setFecha($rta["fecha"]);
                $Cabecera -> setProveedor($rta["proveedor"]);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($Cabecera);
                $entityManager->flush();

                ///////el nro de orden corresponde con el id de la cabecera
                $orden = $Cabecera->getId();

                 return $this->redirect("/ingr_linea/{$orden}/agregar");
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


        ///////////////////formulario de orden
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

        $formularioIngreso->add('save', SubmitType::class, array('label' => 'Agregar Lineas'));

        $formularioIngreso = $formularioIngreso->getForm();

        $formularioIngreso->handleRequest($request);

        ////////////respuesta del formulario de articulos

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


            return $this->redirect("/ingr_linea/{$orden}/agregar");

        }


        /////////formulario cabecera

        $formularioCabecera = $this->createFormBuilder()
            ->add('nombreForm', HiddenType::class,array('attr' => array('value' => 'editarCabecera')))
            ->add('fecha', DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd','attr' => array("value" => date("Y-m-d") ) ))
            ->add('proveedor', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Siguiente'))
            ->getForm();

        /////////respuesta formulario cabecera
        $formularioCabecera->handleRequest($request);

        if ( $formularioCabecera->isSubmitted() && $formularioCabecera->isValid() ) {

            $rta = $formularioCabecera->getData();

            $proveedor = $rta["proveedor"];
            $fecha = $rta["fecha"];
            $nombreForm = $rta["nombreForm"];

            $Cabecera = new ICabecera();

            if($nombreForm == "editarCabecera") {


                $entityManager = $this->getDoctrine()->getManager();
                $ICabecera = $entityManager->getRepository(ICabecera::class)->find($orden);

                $ICabecera -> setFecha($rta["fecha"]);
                $ICabecera -> setProveedor($rta["proveedor"]);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($Cabecera);
                $entityManager->flush();

                ///////el nro de orden corresponde con el id de la cabecera
                $orden = $Cabecera->getId();


                return $this->redirect("/ingr_linea/{$orden}/cabecera");
            }


        }


        $repCabecera = $this->getDoctrine()->getRepository(ICabecera::class);
        $cabe = $repCabecera->find($orden);


        //////formulario para quitar lineas

        $lineas = $this->getDoctrine()->getRepository(ILineas::class);
        $lineas = $lineas->findBy( ['orden' => $orden] );

        $formPedido = $this->createFormBuilder();

        foreach ($lineas as $a ) {
            $formPedido->add($a->getId(), SubmitType::class, array('label' => 'Eliminar línea'));
        }

        $formPedido = $formPedido->getForm();

        $formPedido->handleRequest($request);


        /////////respuesta formulario quitar lineas

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


        //////////////formulario enviar orden

        $formularioOrden = $this -> createFormBuilder();

        $formularioOrden -> add('nombreForm', HiddenType::class, array( 'attr' => array('value' => 'formularioOrden' ) ) );
        $formularioOrden -> add('envOrden', SubmitType::class, array( 'label' => 'Enviar orden', 'attr' => array('class' => 'btn btn-primary') ) );

        $formularioOrden = $formularioOrden -> getForm();
        $formularioOrden -> handleRequest($request);


        /////////////////respuesta de formulario enviar orden
        if ($formularioOrden->isSubmitted() && $formularioOrden->isValid() ) {


            $emLines = $this->getDoctrine() -> getManager();

            $iCabe = $emLines -> getRepository(ICabecera::class)->find($orden);
            $iCabe -> setEstado(1);
            $emLines->flush();


            $ilines = $emLines -> getRepository(ILineas::class)->findByOrden($orden);

            $emStock = $this -> getDoctrine() -> getManager();

           foreach ($ilines as $line){

               $stock = $emStock -> getRepository(stock::class) -> findByIdArticulo($line->getIdArticulo());
               $suma = $stock[0]->getCantidad() + $line -> getCantidad();

              // $stock[0] -> setCantidad($suma);
               //$emStock->flush();

           }

            return $this->redirect("/");
        }


        return $this->render('ingreso/ingr_linea.html.twig', [
            'formularioIngreso' => $formularioIngreso->createView(),
            'formularioCabecera' => $formularioCabecera->createView(),
            'formPedido' => $formPedido->createView(),
            'formularioOrden' => $formularioOrden -> createView(),
            'listaArticulo' => $listaArticulos,
            'lineas' => $lineas,
            'activar' => $activar,
            'orden' => $orden,
            'cabecera' => $cabe,
        
                 ]);


    }








}
