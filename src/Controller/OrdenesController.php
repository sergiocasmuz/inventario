<?php

namespace App\Controller;

use App\Entity\ICabecera;
use App\Entity\ILineas;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;

class OrdenesController extends AbstractController
{
    /**
     * @Route("/ordenes", name="ordenes")
     */
    public function index(Request $request)
    {

        $em = $this -> getDoctrine() -> getManager();
        $icabecera = $em -> getRepository(ICabecera::class) -> findAll();


        $formularioEstado = $this -> createFormBuilder();



        foreach ($icabecera as $a){

            switch ($a->getEstado()){

                case 0: $colorBTN="btn btn-secondary";
                        $textoBTN="En proceso";
                    break;

                case 1: $colorBTN="btn btn-danger";
                    $textoBTN="Pendiente";
                    break;

                case 2: $colorBTN="btn btn-success";
                    $textoBTN="Aprobado";
                    break;

            }

            $formularioEstado -> add('nombreForm', HiddenType::class, array('attr' => array("value" => 'estadoForm' )) );
            $formularioEstado -> add('id_'.$a->getId(), HiddenType::class, array('attr' => array("value" => $a->getId() )));
            $formularioEstado -> add($a->getId(), SubmitType::class,
                array("label" => $textoBTN,
                    'attr' => array('class' => $colorBTN )) );

            $ilineas = $em -> getRepository(ILineas::class) -> findByOrden($a->getId());

        }

        $formularioEstado = $formularioEstado->getForm();
        $formularioEstado->handleRequest($request);


        if ($formularioEstado->isSubmitted() && $formularioEstado->isValid() ) {

            $emLines = $this->getDoctrine() -> getManager();
            $orden=$formularioEstado->getClickedButton()->getName();
            $iCabe = $emLines -> getRepository(ICabecera::class)->find($orden);

            $estado = $iCabe->getEstado();

            switch ($estado){

                case 0:
                    break;

                case 1:
                    $iCabe -> setEstado(2);
                    $emLines->flush();
                    break;

                case 2:

                    break;


            }





        }


        return $this->render('ordenes/index.html.twig', [
            'ordenes' => $icabecera,
            'lineas' => $ilineas,
            'formularioEstado' => $formularioEstado -> createView()
        ]);
    }
}
