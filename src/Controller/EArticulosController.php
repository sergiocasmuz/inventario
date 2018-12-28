<?php

namespace App\Controller;

use App\Entity\ECabecera;
use App\Entity\ELineas;
use App\Form\ElineasType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityRepository;

class EArticulosController extends AbstractController
{
    /**
     * @Route("/agregar/agregar/{orden}/{mensaje}", name="agregar_articulos")
     */

    public function linea(Request $request ,$orden, $mensaje)
    {
      $em = $this -> getDoctrine() -> getManager();
      /* *************** FORMULARIO QUITAR LINEAS ****************************** */

      $lineas = $this->getDoctrine()->getRepository(ELineas::class);
      $lineas = $lineas->findByOrden($orden);

      $ecabe = $em -> getRepository(ECabecera::class) -> find($orden);
      if($ecabe->getEstado() == 0 || $ecabe->getEstado() == 3 ){$act = false; }else{ $act = true;}  ///////corrobora el estado  2 = aprobado

      $formPedido = $this->createFormBuilder();

      foreach ($lineas as $a ) {
          $formPedido->add($a->getId(), SubmitType::class, array('label' => 'Eliminar línea', 'attr' => array("disabled" => $act)));
      }

      $formPedido = $formPedido->getForm();
      $formPedido = $formPedido-> handleRequest($request);

      /* *************** RESPUESTA DE "QUITAR LINEAS" ************************** */

      if ($formPedido->isSubmitted() && $formPedido->isValid() && $act == false) {

          $rta = $formPedido -> getData();


          $idBorrar=$formPedido->getClickedButton()->getName();

          $eLineas = $em->getRepository(ELineas::class)->find($idBorrar);

          $em->remove($eLineas);
          $em->flush();

          $activar = "quitar";
          return $this->redirect("/agregar/agregar/{$orden}");
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

          $iCabe = $emLines -> getRepository(ECabecera::class)->find($orden);
          $iCabe -> setEstado(1);
          $em->flush();

          $ilines = $emLines -> getRepository(ELineas::class)->findByOrden($orden);
          $em = $this -> getDoctrine() -> getManager();

          return $this->redirect("/ordenEntrega");
      }

      return $this->render('e_articulos/articulos.html.twig', [
          'orden' => $orden,
          'cabecera' => $ecabe,
          'lineas' => $lineas,
          'mensaje' => $mensaje,
          'formPedido' => $formPedido -> createView(),
          'formularioOrden' => $formularioOrden -> createView()

               ]);

    }

}
