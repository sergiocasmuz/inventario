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
      $marca = "";
      $familia ="";

      $em = $this -> getDoctrine() -> getManager();
      $articulos = $em -> getRepository(Articulos::class)->findAll();

      $familia = $em -> getRepository(Familia::class) -> findBy(array(), array('familia' => "ASC") );

      $marca = $em -> getRepository(Marca::class) -> findBy(array(), array('marca' => "ASC") );


      $cFamilia = count($familia);

      $listaFamilia[""] = false;
      for ($i=0; $i < $cFamilia; $i++) {
              $listaFamilia[$familia[$i]->getFamilia()] = $familia[$i]->getFamilia();
      }



      $cMarca = count($marca);

      $listaMarca[""] = 'false';
      for ($i=0; $i < $cMarca; $i++) {
              $listaMarca[$marca[$i]->getMarca()] = $marca[$i]->getMarca();
      }




      /* ********************************************************************** */
      /* *************** FORMULARIO DE MARCA ********************************* */
      /* ********************************************************************* */

      $formArticulos = $this -> createFormBuilder()
      -> add('articulo', TextType::class)
      -> add('familia', ChoiceType::class, array('choices' => array('Seleccioná una familia' => $listaFamilia )  ) )
      -> add('marca', ChoiceType::class, array('choices' => array('Seleccioná una marca' => $listaMarca )  ) )
      -> add('modelo', TextType::class)
      -> add('detalle', TextType::class)
      -> add('save', SubmitType::class, array('label' => 'Guardar'));

      $formArticulos = $formArticulos->getForm();

        $formArticulos = $formArticulos -> handleRequest($request);

      if ($formArticulos->isSubmitted() && $formArticulos->isValid()) {

          $rta = $formArticulos->getData();


          /////////////////articulos//////////////////
          $art = new Articulos();

          $art -> setFamilia($rta["familia"]);
          $art -> setArticulo($rta["articulo"]);
          $art -> setMarca($rta["marca"]);
          $art -> setModelo($rta["modelo"]);
          $art -> setDetalle($rta["detalle"]);

          $em->persist($art);
          $em->flush();


          //////////////////stock//////////////////////
          $stock = new Stock();

          $stock -> setFamilia($rta["familia"]);
          $stock -> setArticulo($rta["articulo"]);
          $stock -> setMarca($rta["marca"]);
          $stock -> setModelo($rta["modelo"]);
          $stock -> setDetalle($rta["detalle"]);
          $stock -> setCantidad(0);

          print_r($stock);

          $em->persist($stock);
          $em->flush();


          return $this->redirect("articulos");
    }


        return $this->render('gestionar_articulos/index.html.twig', [
            'formArticulo' => $formArticulos -> createView(),
            'articulos' => $articulos,
        ]);
    }
}
