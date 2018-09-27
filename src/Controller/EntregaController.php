<?php

namespace App\Controller;

use App\Entity\Articulos;
use App\Entity\ECabecera;
use App\Entity\ELineas;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EntregaController extends AbstractController
{
    /**
     * @Route("/solicitud", name="entrega2")
     */
    public function index(Request $request)
    {

      $em = $this -> getDoctrine() -> getManager();

        $formularioCabecera = $this->createFormBuilder()
            ->add('fecha', DateType::class)
            ->add('dependenciaDeDestino', TextType::class)
            ->add('recibe', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Siguiente'))
            ->getForm();

        $formularioCabecera->handleRequest($request);

        if ($formularioCabecera->isSubmitted() && $formularioCabecera->isValid()) {

            $cabe = $formularioCabecera->getData();

            $formCabe = new ECabecera();

            $formCabe -> setFecha($cabe["fecha"]);
            $formCabe -> setDestino($cabe["dependenciaDeDestino"]);
            $formCabe -> setRecibe($cabe["recibe"]);
            $formCabe -> setEstado(0);

            $em -> persist($formCabe);
            $em -> flush();

            ///////el nro de orden corresponde con el id de la cabecera
            $orden = $formCabe->getId();
            return $this->redirect("/orden/{$orden}");
        }

        return $this->render('entrega/entr_cabecera.html.twig',
            ['formularioCabecera' => $formularioCabecera->createView()]);

    }


    /**
     * @Route("/entr_linea/{orden}", name="entrega3a")
     */


    public function linea($orden)
    {
      $em = $this -> getDoctrine() -> getManager();
      /* *************** FORMULARIO NUEVA ORDEN (LINEAS)******************** */

      $ecabe = $em -> getRepository(ECabecera::class) -> find($orden);
      if($ecabe->getEstado() == 2 ){$act = true; }else{ $act =false;}

        $listaArticulos = $this->getDoctrine()->getRepository(Articulos::class)->findAll();


      $formularioIngreso = $this->createFormBuilder();


     $articulosTotales = 0;
     $articulosTotales = count($listaArticulos);

      for($i=0; $i < $articulosTotales; $i++) {


        $ArticuloEnLineas = $em -> getRepository(ELineas::class) -> findLineas($listaArticulos[$i]->getId(),$orden);

        if( empty($ArticuloEnLineas)   ){  $rta = 0; }
          else{
                  $rta = $ArticuloEnLineas[0]->getCantidad();
              }

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


              $eLineas->setOrden($orden);
              $eLineas->setIdArticulo($respuesta["idArticulo".$f]);
              $eLineas->setCantidad($respuesta["cantidad".$f]);
              $eLineas->setArticulo($respuesta["articulo".$f]);
              $eLineas->setMarca($respuesta["marca".$f]);
              $eLineas->setModelo($respuesta["modelo".$f]);
              $eLineas->setFamilia($respuesta["familia".$f]);

              $idArt = $respuesta["idArticulo".$f];

              $query = $em -> createQuery("SELECT u FROM App\Entity\ELineas u WHERE u.orden = '$orden' and u.idArticulo= '$idArt' ");

              $rtaDQL = $query->getResult();

              $existencia = count($rtaDQL);

              if($existencia == 0){

                      if($respuesta["cantidad".$f] != 0){

                          $em->persist($eLineas);
                          $em->flush();
                      }
              }
              else{
                      if($respuesta["cantidad".$f] != 0){

                          $elRep2 = $em -> getRepository(ELineas::class)->find($rtaDQL[0] -> getId());

                          $elRep2 -> setCantidad($respuesta["cantidad".$f]);
                          $em->flush();
                      }
              }


          }


          return $this->redirect("/entr_linea/{$orden}");

      }


      /* *************** FORMULARIO DE CABECERA ******************************** */


      $cab = $em -> getRepository(ECabecera::class) -> find($orden);

      $editarCabecera = $this->createFormBuilder();

      $editarCabecera ->add('nombreForm', HiddenType::class,array('attr' => array('value' => 'editarCabecera')));
      $editarCabecera ->add('fecha', DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd','attr' => array("value" => date("Y-m-d") )));
      $editarCabecera ->add('proveedor', TextType::class, array('attr' => array('value'=> $cab->getProveedor())) );
      $editarCabecera ->add('receptor', TextType::class, array('attr' => array('value'=> $cab->getReceptor())));
      $editarCabecera ->add('remito', TextType::class, array('attr' => array('value'=> $cab->getRemito())));
      $editarCabecera ->add('suministro', TextType::class, array('attr' => array('value'=> $cab->getSuministro())));

      $editarCabecera ->add('save', SubmitType::class, array('label' => 'Siguiente', 'attr' => array("disabled" => $act)  ));
      $editarCabecera = $editarCabecera ->getForm();

      $editarCabecera->handleRequest($request);

      /* *************** RESPUESTA DE "FORMULARIO DE CABECERA" ***************** */


      if ( $editarCabecera->isSubmitted() && $editarCabecera->isValid() && $act == false ) {

          $rta = $editarCabecera->getData();

              $eManager = $this->getDoctrine()->getManager();
              $ECabecera = $eManager->getRepository(ECabecera::class)->find($orden);

              $ECabecera -> setFecha($rta["fecha"]);
              $ECabecera -> setProveedor($rta["proveedor"]);
              $ECabecera -> setReceptor($rta["receptor"]);
              $ECabecera -> setRemito($rta["remito"]);
              $ECabecera -> setSuministro($rta["suministro"]);

              $eManager->persist($ECabecera);
              $eManager->flush();

              ///////el nro de orden corresponde con el id de la cabecera
              $orden = $ECabecera->getId();

              return $this->redirect("/entr_linea/{$orden}");

      }

      $repCabecera = $this->getDoctrine()->getRepository(ECabecera::class);
      $cabe = $repCabecera->find($orden);

      /* *************** FORMULARIO QUITAR LINEAS ****************************** */

      $lineas = $this->getDoctrine()->getRepository(ELineas::class);
      $lineas = $lineas->findByOrden($orden);

      $formPedido = $this->createFormBuilder();


      foreach ($lineas as $a ) {


          $formPedido->add($a->getId(), SubmitType::class, array('label' => 'Eliminar línea', 'attr' => array("disabled" => $act)));
      }

      $formPedido = $formPedido->getForm();

      $formPedido = $formPedido-> handleRequest($request);


      /* *********************************************************************** */
      /* *************** RESPUESTA DE "QUITAR LINEAS" ************************** */
      /* *********************************************************************** */

      if ($formPedido->isSubmitted() && $formPedido->isValid() && $act == false) {

          $rta = $formPedido -> getData();


          $idBorrar=$formPedido->getClickedButton()->getName();




          $eLineas = $em->getRepository(ELineas::class)->find($idBorrar);

          $em->remove($eLineas);
          $em->flush();

          $activar = "quitar";
          //return $this->redirect("/ingr_linea/{$orden}/quitar");
      }


      /* *********************************************************************** */
      /* ************ FORMULARIO CONFIRMAR ORDEN ******************************* */
      /* *********************************************************************** */

      $formularioOrden = $this -> createFormBuilder();

      $formularioOrden -> add('nombreForm', HiddenType::class, array( 'attr' => array('value' => 'formularioOrden' ) ) );
      $formularioOrden -> add('envOrden', SubmitType::class, array( 'label' => 'Terminar orden', 'attr' => array('class' => 'btn btn-primary') ) );

      $formularioOrden = $formularioOrden -> getForm();
      $formularioOrden -> handleRequest($request);


      /* *************** RESPUESTA DE FORMULARIO CONFIRMAR ORDEN *************** */

      if ($formularioOrden->isSubmitted() && $formularioOrden->isValid() && $act == false ) {


          $emLines = $this->getDoctrine() -> getManager();

          $iCabe = $emLines -> getRepository(ECabecera::class)->find($orden);
          $iCabe -> setEstado(1);
          $em->flush();


          $ilines = $emLines -> getRepository(ELineas::class)->findByOrden($orden);

          $em = $this -> getDoctrine() -> getManager();



          return $this->redirect("control");
      }


      $cabe = $em -> getRepository(ECabecera::class)->find($orden);

      return $this->render('entrega/entr_linea.html.twig', [
          'formularioIngreso' => $formularioIngreso->createView(),
          'editarCabecera' => $editarCabecera->createView(),
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
