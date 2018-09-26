<?php

namespace App\Controller;

use App\Entity\Familia;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
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


      $formFamilia = $this -> createFormBuilder();

      $formFamilia -> add('familia', TextType::class);
      $formFamilia -> add('save', SubmitType::class, array('label' => 'Guardar'));
      $formFamilia = $formFamilia->getForm();

      $formFamilia = $formFamilia -> handleRequest($request);

        if ($formFamilia->isSubmitted() && $formFamilia->isValid()) {
            $rta = $formFamilia->getData();

              $fam = new Familia();
              $fam ->setFamilia($rta["familia"]);

              $em -> persist($fam);
              $em -> flush();

              return $this->redirect("articulos");


      }

        return $this->render('gestionar_familias/index.html.twig', [
        		'formFamilia' =>$formFamilia -> createView(),
        		'familia' => $familia
        ]);
    }




    /**
     * @Route("gestionar/familiasBorrar/{idFamilia}", name="familia_borrar")
     */
    public function borrar(Request $request, $idFamilia)
    {

      $em = $this -> getDoctrine() -> getManager();
      $familia = $em -> getRepository(Familia::class) -> find($idFamilia);


              $em -> remove($familia);
              $em -> flush();

              return $this->redirect("/gestionar/familias");


    }


    /**
     * @Route("gestionar/familiasEditar/{idFamilia}", name="familia_editar")
     */
    public function editar(Request $request, $idFamilia)
    {

      $em = $this -> getDoctrine() -> getManager();
      $familia = $em -> getRepository(Familia::class) -> find($idFamilia);
      $familiaList = $em -> getRepository(Familia::class) -> findBy(array(), array('familia' => 'ASC') );

      $formEditar = $this -> createFormBuilder();

      $formEditar -> add('familia', TextType::class, array('attr' => array('value' => $familia->getFamilia() )) );
      $formEditar -> add('save', SubmitType::class, array('label' => 'Guardar'));
      $formEditar = $formEditar -> getForm();

      $formEditar = $formEditar -> handleRequest($request);

        if ($formEditar->isSubmitted() && $formEditar->isValid()) {
            $rta = $formEditar->getData();

              $familia ->setFamilia($rta["familia"]);

              $em -> persist($familia);
              $em -> flush();

              return $this->redirect("/gestionar/familias");
      }



              return $this->render('gestionar_familias/editar.html.twig', [
                  'formEditar' =>$formEditar -> createView(),
                  'familia' => $familiaList,
                  'idFamilia' => $familia->getId()
              ]);


    }



}
