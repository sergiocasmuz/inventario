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
use App\Entity\NrosIdentificacion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
      -> add('familia', ChoiceType::class, array('choices' => array('Seleccioná una familia' => $listaFamilia) ) )

      -> add('marca', ChoiceType::class, array('choices' => array('Seleccioná una marca' => $listaMarca ),

      'choice_attr' => function($choiceValue, $key, $value) {
        return ['value' =>  $value, 'class' => $key];
        },

      ) )




      -> add('modelo', TextType::class)
      -> add('detalle', TextareaType::class)
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

          $idArticulo = $art->getId();

          //////////////////stock//////////////////////
          $stock = new Stock();

          $stock -> setFamilia($rta["familia"]);
          $stock -> setIdArticulo($idArticulo);
          $stock -> setArticulo($rta["articulo"]);
          $stock -> setMarca($rta["marca"]);
          $stock -> setModelo($rta["modelo"]);
          $stock -> setDetalle($rta["detalle"]);
          $stock -> setCantidad(0);

          $em->persist($stock);
          $em->flush();


          return $this->redirect("articulos");
    }


        return $this->render('gestionar_articulos/index.html.twig', [
            'formArticulo' => $formArticulos -> createView(),
            'articulos' => $articulos,
        ]);
    }


    /**
     * @Route("gestionar/articuloBorrar/{idArticulo}", name="articulo_borrar")
     */
    public function borrar(Request $request, $idArticulo)
    {

      $em = $this -> getDoctrine() -> getManager();
      $articulo = $em -> getRepository(Articulos::class) -> find($idArticulo);


              $em -> remove($articulo);
              $em -> flush();

              return $this->redirect("/gestionar/articulos");


    }


    /**
     * @Route("gestionar/articuloEditar/{idArticulo}", name="articulo_editar")
     */
    public function editar(Request $request, $idArticulo)
    {
      $em = $this -> getDoctrine() -> getManager();
      $articulo = $em -> getRepository(Articulos::class) -> find($idArticulo);
      $articuloList = $em -> getRepository(Articulos::class) -> findBy(array(), array('articulo' => 'ASC') );

      $familia = $em -> getRepository(Familia::class) -> findBy(array(), array('familia' => "ASC") );
      $marca = $em -> getRepository(Marca::class) -> findBy(array(), array('marca' => "ASC") );


      $cFamilia = count($familia);

      $listaFamilia[$articulo->getFamilia()] = false;
      for ($i=0; $i < $cFamilia; $i++) {
              $listaFamilia[$familia[$i]->getFamilia()] = $familia[$i]->getFamilia();
      }


      $cMarca = count($marca);

      $listaMarca[$articulo->getMarca()] = 'false';
      for ($i=0; $i < $cMarca; $i++) {
              $listaMarca[$marca[$i]->getMarca()] = $marca[$i]->getMarca();
      }


      $formEditar = $this -> createFormBuilder()

      -> add('articulo', TextType::class, array( 'attr' => array( 'value' => $articulo->getArticulo() ) ) )
      -> add('familia', ChoiceType::class, array('choices' => array('Seleccioná una familia' => $listaFamilia) ) )

      -> add('marca', ChoiceType::class, array('choices' => array('Seleccioná una marca' => $listaMarca ),

      'choice_attr' => function($choiceValue, $key, $value) {
        return ['value' =>  $value, 'class' => $key];
        },

      ) )


      -> add('modelo', TextType::class, array( 'attr' => array( 'value' => $articuloList[0]->getModelo() ) ))
      -> add('detalle', TextType::class, array( 'attr' => array( 'value' => $articuloList[0]->getDetalle() ) ))
      -> add('save', SubmitType::class, array('label' => 'Guardar'));

      $formEditar = $formEditar->getForm();

      $formEditar = $formEditar -> handleRequest($request);

          if ($formEditar->isSubmitted() && $formEditar->isValid()) {
              $rta = $formEditar->getData();

                $articulo -> setArticulo($rta["articulo"]);
                $articulo -> setFamilia($rta["familia"]);
                $articulo -> setMarca($rta["marca"]);
                $articulo -> setModelo($rta["modelo"]);
                $articulo -> setDetalle($rta["detalle"]);

                $em -> persist($articulo);
                $em -> flush();

                return $this->redirect("/gestionar/articulos");
            }




            $agregarNro = $this -> createFormBuilder()

            -> add('idArt', HiddenType::class)
            -> add('nro', TextType::class)
            -> add('save', SubmitType::class, array('label'=>'Guardar número' ,'attr' => array('class' => 'btn btn-primary')));

            $agregarNro = $agregarNro->getForm();
            $agregarNro = $agregarNro -> handleRequest($request);

            if ($agregarNro->isSubmitted() && $agregarNro->isValid()) {

                  $rta = $agregarNro -> getData();

                  //$nrosIdentiaficacion = $em -> getRepository(NrosIdentificacion::class)->;
                $nrosIdentiaficacion = new NrosIdentificacion();

                $nrosIdentiaficacion -> setIdArticulo($rta["idArt"]);
                $nrosIdentiaficacion -> setNroArticulo($rta["nro"]);

                  $em -> persist($nrosIdentiaficacion);
                  $em -> flush();


            }




            return $this->render('gestionar_articulos/editar.html.twig', [
                'formEditar' =>$formEditar -> createView(),
                'articulo' => $articuloList,
                'idArticulo' => $articulo->getId(),
                'agregarNro' => $agregarNro -> createView()
            ]);

    }


}
