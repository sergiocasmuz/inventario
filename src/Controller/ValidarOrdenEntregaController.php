<?php

namespace App\Controller;

use App\Entity\ECabecera;
use App\Entity\ELineas;
use App\Entity\stock;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityRepository;

class ValidarOrdenEntregaController extends AbstractController
{
    /**
     * @Route("/validar/orden/entrega/{orden}", name="validar_orden_entrega")
     */
    public function index(Request $request, $orden)
    {

/* *************** FUNCION ******************************** */
      function restarStock($orden,$em){

      $elines = $em -> getRepository(ELineas::class)->findByOrden($orden);
      foreach ($elines as $line){
              $stock = $em -> getRepository(stock::class) -> findByIdArticulo($line->getIdArticulo());
              $resta = ($stock[0]->getCantidad()) - ($line -> getCantidad());

              $stock[0] -> setCantidad($resta);
              $em->flush();
              }
      }

      /* *************** FORMULARIO DE CABECERA ******************************** */
      $em = $this -> getDoctrine() -> getManager();
      $cab = $em -> getRepository(ECabecera::class) -> find($orden);

      $editarCabecera = $this->createFormBuilder();

      $editarCabecera ->add('nombreForm', HiddenType::class,array('attr' => array('value' => 'editarCabecera')));
      $editarCabecera ->add('fecha', DateType::class,array('widget' => 'single_text', 'format' => 'dd-mm-yyyy','attr' => array("value" => date("d-m-Y") )));
      $editarCabecera ->add('destino', TextType::class, array('attr' => array('value'=> $cab->getDestino())) );
      $editarCabecera ->add('recibe', TextType::class, array('attr' => array('value'=>  $cab->getRecibe(), "autofocus" => true )) );
      $editarCabecera ->add('legajo', IntegerType::class, array('attr' => array('value'=>  $cab->getLegajo())) );

      $editarCabecera ->add('save', SubmitType::class, array('label' => 'Siguiente', 'attr' => array('class' => 'btn btn-primary') ));
      $editarCabecera = $editarCabecera ->getForm();

      $editarCabecera->handleRequest($request);

      /* *************** RESPUESTA DE "FORMULARIO DE CABECERA" ***************** */


      if ( $editarCabecera->isSubmitted() && $editarCabecera->isValid() ) {

          $rta = $editarCabecera->getData();

              $eManager = $this->getDoctrine()->getManager();
              $ECabecera = $eManager->getRepository(ECabecera::class)->find($orden);

              $ECabecera -> setFecha($rta["fecha"]);
              $ECabecera -> setDestino($rta["destino"]);
              $ECabecera -> setRecibe($rta["recibe"]);
              $ECabecera -> setLegajo($rta["legajo"]);
              $ECabecera -> setEstado(5); ///////finalizar

              restarStock($orden,$em);

              $eManager->persist($ECabecera);
              $eManager->flush();

              ///////el nro de orden corresponde con el id de la cabecera
              $orden = $ECabecera->getId();

              return $this->redirect("/ordenEntrega");
      }

      $repCabecera = $this->getDoctrine()->getRepository(ECabecera::class);
      $cabe = $repCabecera->find($orden);

        return $this->render('validar_orden_entrega/index.html.twig', [
            'editarCabecera' => $editarCabecera ->createView(),
        ]);
    }
}
