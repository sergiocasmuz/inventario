<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Articulos;
use App\Entity\NrosIdentificacion;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class GestionarCoodigosController extends AbstractController
{
    /**
     * @Route("/gestionar/codigos/{articulo}", name="gestionar_coodigos")
     */
    public function index(Request $request, $articulo)

    {
        $validacion="";
        $em = $this -> getDoctrine() -> getManager();
        $Articulo = $em -> getRepository(Articulos::class) -> find($articulo);

        $numeros = $em -> getRepository(NrosIdentificacion::class) -> findByIdArticulo($articulo);



        /********************** formulario numeros de articulo ************************************/

        $formNumero = $this -> createFormBuilder();
        $formNumero -> add('nroArticulo', TextType::class);
        $formNumero -> add('Guardar', SubmitType::class, array('attr' => array('class' => 'btn btn-primary')));

        $formNumero = $formNumero -> getForm();
        $formNumero -> handleRequest($request);

        if ($formNumero->isSubmitted() && $formNumero->isValid() ) {

          $rta = $formNumero -> getData();


          $buscar = $em -> getRepository(NrosIdentificacion::class) -> findByNroArticulo($rta["nroArticulo"]);


          if(count($buscar) == 0){

            $adentro = new NrosIdentificacion();
            $adentro -> setIdArticulo($articulo);
            $adentro -> setNroArticulo($rta["nroArticulo"]);
            $adentro -> setNroUnico(0);

            $em -> persist($adentro);
            $em -> flush();
            return $this->redirect("/gestionar/codigos/{$articulo}");
          }
          else{

                $art = $em -> getRepository(Articulos::class) -> find($buscar[0]->getIdArticulo());
                $validacion="El número ya se encuentra en uso para el artículo: #".$art->getId()." (".$art->getArticulo().")";

              }


        //  return $this->redirect("/gestionar/codigos/{$articulo}");
            }



        return $this->render('gestionar_coodigos/index.html.twig', [
            'controller_name' => 'GestionarCoodigosController',
            'articulo' => $Articulo,
            'numeros' => $numeros,
            'formNumero' => $formNumero -> createView(),
            'validacion' => $validacion
        ]);
    }



    /**
     * @Route("/gestionar/borrarNro/{id}/{idArt}", name="borrar_coodigos")
     */
    public function borrar($id, $idArt)

    {
      $em = $this -> getDoctrine() -> getManager();
      $Nro = $em -> getRepository(NrosIdentificacion::class) -> find($id);

      $em -> remove($Nro);
      $em -> flush();

      return $this->redirect("/gestionar/codigos/{$idArt}");
    }



}
