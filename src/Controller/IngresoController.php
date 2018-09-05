<?php

namespace App\Controller;

use App\Entity\Articulos;
use App\Entity\ICabecera;
use App\Entity\ILineas;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IngresoController extends AbstractController
{

    /**
     * @Route("/ingr_cabecera", name="entrega3")
     */
    public function index(Request $request)
    {

        $formularioCabecera = new ICabecera();

        $formularioCabecera = $this->createFormBuilder($formularioCabecera)
            ->add('fecha', DateType::class)
            ->add('proveedor', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Siguiente'))
            ->getForm();

        $formularioCabecera->handleRequest($request);

        if ($formularioCabecera->isSubmitted() && $formularioCabecera->isValid()) {

            $articulo = $formularioCabecera->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($articulo);
            $entityManager->flush();

            ///////el nro de orden corresponde con el id de la cabecera
            $orden = $articulo->getId();

            return $this->redirect("/ingr_linea/{$orden}");
        }

        return $this->render('ingreso/ingr_cabecera.html.twig',
            ['formularioCabecera' => $formularioCabecera->createView()]);
    }




    /**
     * @Route("/ingr_linea/{orden}", name="entrega")
     */

    public function linea(Request $request, $orden)
    {

        $repository = $this->getDoctrine()->getRepository(Articulos::class);
        $listaArticulos = $repository->findAll();


        ///////////////////formulario de orden
        $formularioIngreso = $this->createFormBuilder();

        foreach($listaArticulos as $articulo ) {

            $idArt = $articulo->getId();


            $formularioIngreso->add('idArticulo'.$idArt, HiddenType::class,
                array('attr' => array('value' => $idArt )));

            $formularioIngreso->add('cantidad'.$idArt, TextType::class);

            $formularioIngreso->add('articulo'.$idArt, HiddenType::class,
                array('attr' => array('value' => $articulo->getArticulo())));

            $formularioIngreso->add('marca'.$idArt, HiddenType::class,
                array('attr' => array('value' => $articulo->getMarca())));

            $formularioIngreso->add('modelo'.$idArt, HiddenType::class,
                array('attr' => array('value' => $articulo->getModelo())));

        }

        $formularioIngreso->add('save', SubmitType::class, array('label' => 'Agregar Lineas'));

        $formularioIngreso = $formularioIngreso->getForm();

        $formularioIngreso->handleRequest($request);




        ////////////respuesta del formulario de articulos

        if ($formularioIngreso->isSubmitted() && $formularioIngreso->isValid()) {

            $respuesta = $formularioIngreso->getData();

            $c1 = 1;
            foreach($respuesta as $id_articulo => $cantidad){


                $idArt =  $respuesta["idArticulo".$c1];
                $cantidad =  $respuesta["cantidad".$c1];
                $marca =  $respuesta["marca".$c1];
                $modelo =  $respuesta["modelo".$c1];
                $articulo =  $respuesta["articulo".$c1];

                $iLineas = new ILineas();

                $iLineas->setOrden($orden);
                $iLineas->setIdArticulo($idArt);
                $iLineas->setMarca($marca);
                $iLineas->setModelo($modelo);
                $iLineas->setArticulo($articulo);
                $iLineas->setCantidad($cantidad);

                $entityManager = $this->getDoctrine()->getManager();

                $entityManager->persist($iLineas);
                $entityManager->flush();



                if( $c1 % 3 == 0){$c1=1;}
                else{$c1++;}


            }


            return $this->redirect("/ingreso");
        }





        return $this->render('ingreso/ingr_linea.html.twig', [
            'formularioIngreso' => $formularioIngreso->createView(),
            'listaArticulo' => $listaArticulos,
            'formularioCabecera' => $formularioCabecera->createView()

        ]);


    }






}
