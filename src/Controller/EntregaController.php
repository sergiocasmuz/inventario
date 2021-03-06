<?php

namespace App\Controller;

use App\Entity\Articulos;
use App\Entity\ECabecera;
use App\Entity\ELineas;
use App\Entity\stock;
use App\Entity\Dependencias;
use App\Form\ElineasType;
use App\Entity\NrosIdentificacion;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
      $dependencias = $em -> getRepository(Dependencias::class) -> findAll();

      $list = array();
      $list[""]="";
      foreach ($dependencias as $dep) {
        $list[$dep -> getDependencia()] = $dep -> getDependencia();
      }

        $formularioCabecera = $this->createFormBuilder()
            ->add('fecha', DateType::class,array('widget' => 'single_text','attr' => array("value" => date("Y-m-d") )))
            ->add('nroDeTicket', IntegerType::class)
            ->add('dependenciaDeDestino', ChoiceType::class, array('choices' => array('Seleccioná una Dependencia' => $list) ) )
            ->add('recibe', HiddenType::class)
            ->add('legajo', HiddenType::class)
            ->add('save', SubmitType::class, array('label' => 'Siguiente'))
            ->getForm();

        $formularioCabecera->handleRequest($request);

        if ($formularioCabecera->isSubmitted() && $formularioCabecera->isValid()) {

            $cabe = $formularioCabecera->getData();

            print_r($cabe["fecha"]);

          //echo date("Y-m-d");

            $formCabe = new ECabecera();

            $formCabe -> setFecha($cabe["fecha"]);
            $formCabe -> setNroTicket($cabe["nroDeTicket"]);
            $formCabe -> setDestino($cabe["dependenciaDeDestino"]);
            $formCabe -> setRecibe("...");
            $formCabe -> setLegajo(0);
            $formCabe -> setEstado(0);

            $em -> persist($formCabe);
            $em -> flush();

            ///////el nro de orden corresponde con el id de la cabecera
            $orden = $formCabe->getId();
            return $this->redirect("/agregar/agregar/{$orden}/0");
        }

        return $this->render('entrega/entr_cabecera.html.twig',
            ['formularioCabecera' => $formularioCabecera->createView()]);

    }




}
