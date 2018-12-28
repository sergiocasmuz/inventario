<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\ELineas;
use App\Entity\ECabecera;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class ImprimirController extends AbstractController
{
    /**
     * @Route("/imprimir/{orden}", name="imprimir")
     */
    public function index(Request $request, $orden)
    {

      $em = $this -> getDoctrine() -> getManager();

      $ECabecera = $em -> getRepository(ECabecera::class) -> find( $orden );
      $ELineas = $em -> getRepository(ELineas::class) -> findByOrden( $orden );


      $cabeForm = $this -> createFormBuilder()

      -> add('fecha', DateType::class, array('widget' => 'single_text', 'format' => 'dd-MM-yyyy', 'attr' => array("value" => date("d-m-Y") )))
      -> add('nroTicket', IntegerType::class, array( 'attr' => array('value' => $ECabecera->getNroTicket())  ))
      -> add('destino', TextType::class, array( 'attr' => array('value' => $ECabecera->getDestino())  ))
      -> add('recibe', TextType::class, array( 'attr' => array('value' => $ECabecera->getRecibe())  ))
      -> add('legajo', IntegerType::class, array( 'attr' => array('value' => $ECabecera->getLegajo())  ))
      -> getForm()
      -> handleRequest($request);


        if ($cabeForm->isSubmitted() && $cabeForm->isValid()) {

            $rta = $cabeForm -> getData();


            $ECabecera -> setFecha($rta["fecha"]);
            $ECabecera -> setDestino($rta["destino"]);
            $ECabecera -> setRecibe($rta["recibe"]);
            $ECabecera -> setNroTicket($rta["nroTicket"]);
            $ECabecera -> setLegajo($rta["legajo"]);

            $em -> persist($ECabecera);
            $em -> flush();

            return $this->redirect("/imprimir/{$orden}");


        }




      return $this->render('imprimir/notaEntrega.html.twig', [
            'cabecera' => $ECabecera,
            'lineas' => $ELineas,
            'cabeForm' => $cabeForm -> createView()
        ]);
    }
}
