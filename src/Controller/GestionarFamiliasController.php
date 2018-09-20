<?php

namespace App\Controller;


use App\Entity\ECabecera;
use App\Entity\ELineas;
use App\Entity\Familia;
use App\Entity\ICabecera;
use App\Entity\ILineas;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GestionarFamiliasController extends AbstractController
{
    /**
     * @Route("gestionar/familias", name="familia")
     */
    public function index(Request $request)
    {

    	$em = $this -> getDoctrine() -> getManager();
    	$familia = $em -> getRepository(Familia::class) -> findBy(array(), array('familia' => 'DESC')) ;


      /* *********************************************************************** */
      /* *************** FORMULARIO DE FAMILIA ********************************* */
      /* *********************************************************************** */

      $formFamilia = $this -> createFormBuilder();

      $formFamilia -> add('nombreForm', HiddenType::class, array('attr' => array('value' => 'formularioFamilia') ));
      $formFamilia -> add('familia', TextType::class);
      $formFamilia -> add('save', SubmitType::class, array('label' => 'Guardar'));
      $formFamilia = $formFamilia->getForm();

      $formFamilia = $formFamilia -> handleRequest($request);

        if ($formFamilia->isSubmitted() && $formFamilia->isValid()) {
            $rta = $formFamilia->getData();

          if($rta[nombreForm] == "formularioFamilia"){

              $fam = new Familia();
              $fam ->setFamilia($rta["familia"]);

              $em -> persist($fam);
              $em -> flush();

              return $this->redirect("articulos");
            }

      }

        return $this->render('gestionar_familias/index.html.twig', [
        		'formFamilia' =>$formFamilia -> createView(),
        		'familia' => $familia
        ]);
    }
}
