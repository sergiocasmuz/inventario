<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Dependencias;

class GestionarDependenciasController extends AbstractController
{
    /**
     * @Route("/gestionar/dependencias", name="gestionar_dependencias")
     */
    public function index(Request $request)
    {

        $em = $this -> getDoctrine() -> getManager();

        $dependencias = $em -> getRepository(Dependencias::class) -> findBy(array(), array('dependencia' => 'ASC'));

        $formulario = $this -> createFormBuilder()
        ->add('nuevaDependencia', TextType::class)
        ->add('guardar', SubmitType::class)
        ->getForm()
        ->handleRequest($request);

        if ($formulario->isSubmitted() && $formulario->isValid() ) {

          $datos = $formulario -> getData();

          $DEP = new Dependencias();
          $DEP -> setDependencia( $datos["nuevaDependencia"] );

          $em -> persist($DEP);
          $em -> flush();

          return $this->redirect("/gestionar/dependencias");

        }

        return $this->render('gestionar_dependencias/index.html.twig', [
            'dependencias' => $dependencias,
            'formulario' => $formulario -> createView()
        ]);
    }



    /**
     * @Route("/gestionar/dependenciasEditar/{id}", name="gestionar_dependencias_editar")
     */
    public function editar(Request $request, $id)
    {

        $em = $this -> getDoctrine() -> getManager();

        $dependencias = $em -> getRepository(Dependencias::class) -> findBy(array(), array('dependencia' => 'ASC'));
        $dependencia = $em -> getRepository(Dependencias::class) -> find($id);

        $formulario = $this -> createFormBuilder()
        ->add('nuevaDependencia', TextType::class, array('attr' => array('value' => $dependencia-> getDependencia() ))  )
        ->add('guardar', SubmitType::class, array('label'=> 'Editar'))
        ->getForm()
        ->handleRequest($request);

        if ($formulario->isSubmitted() && $formulario->isValid() ) {

          $datos = $formulario -> getData();

          $dependencia -> setDependencia($datos["nuevaDependencia"]);

          $em -> persist($dependencia);
          $em -> flush();

          return $this->redirect("/gestionar/dependencias");

        }

        return $this->render('gestionar_dependencias/index.html.twig', [
            'dependencias' => $dependencias,
            'formulario' => $formulario -> createView()
        ]);
    }



    /**
     * @Route("/gestionar/dependenciasBorrar/{id}", name="gestionar_dependencias_borrar")
     */
    public function borrar($id)
    {
      $em = $this -> getDoctrine() -> getManager();
      $dependencias = $em -> getRepository(Dependencias::class) -> find($id);

      $em -> remove($dependencias);
      $em -> flush();

      return $this -> redirect("/gestionar/dependencias");

    }


}
