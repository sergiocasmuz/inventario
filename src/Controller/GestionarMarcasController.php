<?php

namespace App\Controller;

use App\Entity\Articulos;
use App\Entity\Marca;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GestionarMarcasController extends AbstractController
{
    /**
     * @Route("/gestionar/marcas", name="gestionar_marcas")
     */
    public function index(Request $request)
    {

      $em = $this -> getDoctrine() -> getManager();
      $marca = $em -> getRepository(Marca::class) -> findBy(array(), array('marca' => 'ASC' ) );

      $forMarca = $this ->  createFormBuilder()
      -> add('marca', TextType::class)
      -> add('save', SubmitType::class, array ('label'=>'Guardar') );
      $forMarca = $forMarca -> getForm();

      if ($forMarca->isSubmitted() && $forMarca->isValid()) {}



        return $this->render('gestionar_marcas/index.html.twig', [
            'formMarca' => $forMarca -> createView(),
            'marca' => $marca
        ]);
    }



    /**
     * @Route("/gestionar/marcasBorrar/{idMarca}", name="gestionar_marcas_borrar")
     */
    public function borrar($idMarca)
    {

      $em = $this -> getDoctrine() -> getManager();
      $marca = $em -> getRepository(Marca::class) -> find($idMarca);

      $em->remove($marca);
      $em->flush();

      return $this->redirect("/gestionar/marcas");

    }


    /**
     * @Route("/gestionar/marcasEditar/{idMarca}", name="gestionar_marcas_editar")
     */
    public function editar(Request $request,$idMarca)
    {

      $em = $this -> getDoctrine() -> getManager();
      $marcaList = $em -> getRepository(Marca::class) -> findBy(array(), array('marca' => 'ASC' ));
      $marca = $em -> getRepository(Marca::class) -> find($idMarca);

      $formEditar = $this -> createFormBuilder()

      -> add('marca', TextType::class, array('attr' => array('value' => $marca -> getMarca() ) )  )
      -> add('save', SubmitType::class, array('label' => 'Editar'));
      $formEditar = $formEditar -> getForm();

      $formEditar = $formEditar -> handleRequest($request);

      if ($formEditar->isSubmitted() && $formEditar->isValid()) {

        $rta = $formEditar -> getData();
        $marca -> setMarca($rta["marca"]);

        $em->persist($marca);
        $em->flush();
      }


      return $this->render('gestionar_marcas/editar.html.twig', [
          'formEditar' =>$formEditar -> createView(),
          'marca' => $marcaList,
          'idMarca' => $marca->getId()
      ]);

    }
}
