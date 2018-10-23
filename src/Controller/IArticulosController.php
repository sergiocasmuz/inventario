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

class IArticulosController extends AbstractController
{

    /**
     * @Route("/ingreso/articulos/{orden}", name="art")
     */

    public function linea(Request $request, $orden)
    {
            $em = $this -> getDoctrine() -> getManager();
            $repository = $this->getDoctrine()->getRepository(Articulos::class);
            $listaArticulos = $repository->findAll();


      $icabe = $em -> getRepository(ICabecera::class) -> find($orden);

      if (empty($icabe)){ return $this->redirect("/gestion/errores/1");  }
      if($icabe->getEstado() == 2 ){$act = true; }else{ $act =false;}

        /* *************** FORMULARIO QUITAR LINEAS ****************************** */

        $lineas = $this->getDoctrine()->getRepository(ILineas::class);
        $lineas = $lineas->findByOrden($orden);

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



            $entityManager = $this->getDoctrine()->getManager();
            $iLineas = $entityManager->getRepository(ILineas::class)->find($idBorrar);

            $entityManager->remove($iLineas);
            $entityManager->flush();


            return $this->redirect("/ingreso/articulos/{$orden}");
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



            return $this->redirect("control");
        }

        $cabe = $em -> getRepository(ICabecera::class)->find($orden);

        return $this->render('ingreso/articulos.html.twig', [
            'formPedido' => $formPedido->createView(),
            'lineas' => $lineas,
            'orden' => $orden,
            'cabecera' => $icabe,
            'formularioOrden' => $formularioOrden -> createView(),
            'listaArticulo' => $listaArticulos
                 ]);


    }








}
