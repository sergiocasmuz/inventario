<?php

namespace App\Controller;

use App\Entity\Articulos;
use App\Entity\ECabecera;
use App\Entity\ELineas;
use App\Entity\stock;
use App\Form\ElineasType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityRepository;

class EntregaController extends AbstractController
{
    /**
     * @Route("/solicitud", name="entrega2")
     */
    public function index(Request $request)
    {

      $em = $this -> getDoctrine() -> getManager();

        $formularioCabecera = $this->createFormBuilder()
            ->add('fecha', DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd','attr' => array("value" => date("Y-m-d") )))
            ->add('nroDetTicket', IntegerType::class)
            ->add('dependenciaDeDestino', TextType::class)
            ->add('recibe', HiddenType::class)
            ->add('legajo', HiddenType::class)
            ->add('save', SubmitType::class, array('label' => 'Siguiente'))
            ->getForm();

        $formularioCabecera->handleRequest($request);

        if ($formularioCabecera->isSubmitted() && $formularioCabecera->isValid()) {

            $cabe = $formularioCabecera->getData();

            $formCabe = new ECabecera();

            $formCabe -> setFecha($cabe["fecha"]);
            $formCabe -> setNroTicket($cabe["nroDetTicket"]);
            $formCabe -> setDestino($cabe["dependenciaDeDestino"]);
            $formCabe -> setRecibe("...");
            $formCabe -> setLegajo(0);
            $formCabe -> setEstado(0);

            $em -> persist($formCabe);
            $em -> flush();

            ///////el nro de orden corresponde con el id de la cabecera
            $orden = $formCabe->getId();
            return $this->redirect("/entr_linea/{$orden}");
        }

        return $this->render('entrega/entr_cabecera.html.twig',
            ['formularioCabecera' => $formularioCabecera->createView()]);

    }


    /**
     * @Route("/entr_linea/{orden}", name="entrega34")
     */

    public function linea(Request $request ,$orden)
    {
      $em = $this -> getDoctrine() -> getManager();
      /* *************** FORMULARIO NUEVA ORDEN (LINEAS)******************** */

      $ecabe = $em -> getRepository(ECabecera::class) -> find($orden);
      if($ecabe->getEstado() == 0 || $ecabe->getEstado() == 3 ){$act = false; }else{ $act = true;}  ///////corrobora el estado  2 = aprobado

      $listaArticulos = $em -> getRepository(Articulos::class)->findAll();

      $forms = [];
      foreach ($listaArticulos as $articulo) {

        $form = $this-> createForm(ElineasType::class);
        $form->add('save', SubmitType::class, array('label' => 'Agregar linea', 'attr' => array("disabled" => $act)  ));
        $form = $form -> handleRequest($request);


        if (   $form->isSubmitted() && $act == false ) {

          $rtaBTN = $form -> getData();

          foreach ($rtaBTN as $key => $value) { $datos[$key] = $value;  }

          $elineas = new ELineas();

          $elineas->setOrden($orden);
          $elineas->setIdArticulo($datos["idArticulo"]);
          $elineas->setCantidad(1);
          $elineas->setArticulo($datos["articulo"]);
          $elineas->setMarca($datos["marca"]);
          $elineas->setModelo($datos["modelo"]);
          $elineas->setNroSerie($datos["nroSerie"]);
          $elineas->setFamilia($datos["familia"]);

          $em -> persist($elineas);
          $em -> flush();

          return $this->redirect("/entr_linea/{$orden}");


    }


        $form = $form -> createView();
        $forms[$articulo->getId()] = $form;

      }


      /* *************** FORMULARIO DE CABECERA ******************************** */

      $ecabe = $em -> getRepository(ECabecera::class) -> find($orden);

      if($ecabe->getEstado() == 0 || $ecabe->getEstado() == 3){$act = false; }else{ $act = true;}  ///////corrobora el estado  2 = aprobado


      $editarCabecera = $this->createFormBuilder();

      $editarCabecera ->add('nombreForm', HiddenType::class,array('attr' => array('value' => 'editarCabecera')));
      $editarCabecera ->add('fecha', DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd','attr' => array("value" => date("Y-m-d") )));
      $editarCabecera ->add('destino', TextType::class, array('attr' => array('value'=> $ecabe->getDestino())) );
      $editarCabecera ->add('recibe', TextType::class, array('attr' => array('value'=> $ecabe->getRecibe())));
      $editarCabecera ->add('legajo', TextType::class, array('attr' => array('value'=> $ecabe->getLegajo())));

      $editarCabecera ->add('save', SubmitType::class, array('label' => 'Siguiente', 'attr' => array("disabled" => $act)  ));
      $editarCabecera = $editarCabecera ->getForm();

      $editarCabecera->handleRequest($request);

      /* *************** RESPUESTA DE "FORMULARIO DE CABECERA" ***************** */


      if ( $editarCabecera->isSubmitted() && $editarCabecera->isValid() && $act == false ) {

          $rta = $editarCabecera->getData();

              $eManager = $this->getDoctrine()->getManager();
              $ECabecera = $eManager->getRepository(ECabecera::class)->find($orden);

              $ECabecera -> setFecha($rta["fecha"]);
              $ECabecera -> setDestino($rta["destino"]);
              $ECabecera -> setRecibe($rta["recibe"]);
              $ECabecera -> setLegajo($rta["legajo"]);

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
          return $this->redirect("/entr_linea/{$orden}");
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



          return $this->redirect("/ordenEntrega");
      }


      $cabe = $em -> getRepository(ECabecera::class)->find($orden);

      return $this->render('entrega/entr_linea.html.twig', [
          'editarCabecera' => $editarCabecera->createView(),
          'formPedido' => $formPedido->createView(),
          'formularioOrden' => $formularioOrden -> createView(),
          'listaArticulo' => $listaArticulos,
          'lineas' => $lineas,
          'activar' => $act,
          'orden' => $orden,
          'cabecera' => $cabe,
          'forms' => $forms

               ]);

    }

}
