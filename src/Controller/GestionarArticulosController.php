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

class GestionarArticulosController extends AbstractController
{
    /**
     * @Route("/gestionar/articulos", name="gestionar_articulos")
     */
    public function index(Request $request)
    {

      /* ********************************************************************** */
      /* *************** FORMULARIO DE MARCA ********************************* */
      /* ********************************************************************* */

      $em = $this -> getDoctrine() -> getRepository();
      $articulos = $em -> getRepository(Articulos::class)->findBy(aray(), array('articulos' => 'DESC') );

      $formArticulos = $this -> createFormBuilder()
      ->add('articulo', TextType::class);
      ->add('save', SubmitType::class, array('label' => 'Guardar'));

      $formArticulos = $formArticulos -> getForm();

      if ($formArticulos->isSubmitted() && $formArticulos->isValid()) {
          $rta = $formArticulos->getData();

            $articulos ->setFamilia($rta["familia"]);

            $em -> flush();

            return $this->redirect("articulos");

    }







      /* ********************************************************************** */
      /* *************** FORMULARIO DE MARCA ********************************* */
      /* ********************************************************************* */
/*
      $formMarca = $this -> createFormBuilder();


      $formMarca -> add('nombreForm', HiddenType::class, array('attr' => array('value' => 'formularioMarca') ));
      $formMarca -> add('marca', TextType::class);
      $formMarca -> add('save', SubmitType::class, array('label' => 'Guardar'));
      $formMarca = $formMarca->getForm();

      $formMarca = $formMarca->handleRequest($request);

      if ($formMarca -> isSubmitted() && $formMarca -> isValid()) {
          $rta = $formMarca->getData();

          if($rta[nombreForm] == "formularioMarca"){

          $mar = new Marca();
          $mar ->setMarca($rta["familia"]);

          $em -> persist($mar);
          $em -> flush();

          return $this->redirect("articulos");
        }



      }

*/


        return $this->render('gestionar_articulos/index.html.twig', [
            'formArticulo' => $formArticulo -> createView(),
            'articulos' => $articulos,
        ]);
    }
}
