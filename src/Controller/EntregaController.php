<?php

namespace App\Controller;

use App\Entity\Articulos;
use App\Entity\ECabecera;
use App\Entity\ELineas;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EntregaController extends AbstractController
{
    /**
     * @Route("/solicitud", name="entrega2")
     */
    public function index(Request $request)
    {

        $formularioCabecera = new ECabecera();

        $formularioCabecera = $this->createFormBuilder($formularioCabecera)
            ->add('fecha', DateType::class)
            ->add('destino', TextType::class)
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

            return $this->redirect("/orden/{$orden}");
        }

        return $this->render('entrega/entr_cabecera.html.twig',
            ['formularioCabecera' => $formularioCabecera->createView()]);


    }


    /**
     * @Route("/orden/{orden}", name="entrega")
     */

    public function linea(Request $request,$orden)
    {

        $repository = $this->getDoctrine()->getRepository(Articulos::class);
        $listaArticulos = $repository->findAll();


        ///////////////////formulario de orden
        $formulario = $this->createFormBuilder();

        foreach($listaArticulos as $articulo ) {

            $idArt = $articulo->getId();


            $formulario->add('idArticulo'.$idArt, HiddenType::class,
                array('attr' => array('value' => $idArt )));

            $formulario->add('cantidad'.$idArt, TextType::class);

            $formulario->add('articulo'.$idArt, HiddenType::class,
                array('attr' => array('value' => $articulo->getArticulo())));

            $formulario->add('marca'.$idArt, HiddenType::class,
                array('attr' => array('value' => $articulo->getMarca())));

            $formulario->add('modelo'.$idArt, HiddenType::class,
                array('attr' => array('value' => $articulo->getModelo())));

        }

        $formulario->add('save', SubmitType::class, array('label' => 'Agregar Lineas'));

        $formulario = $formulario->getForm();

        $formulario->handleRequest($request);




        ////////////respuesta del formulario de articulos

        if ($formulario->isSubmitted()) {

            $respuesta = $formulario->getData();

            $c1 = 1;
            foreach($respuesta as $id_articulo => $cantidad){

                $idArt =  $respuesta["idArticulo".$c1];
                $cantidad =  $respuesta["cantidad".$c1];
                $marca =  $respuesta["marca".$c1];
                $modelo =  $respuesta["modelo".$c1];
                $articulo =  $respuesta["articulo".$c1];

                $eLineas = new ELineas();

                $eLineas->setOrden($orden);
                $eLineas->setIdArticulo($idArt);
                $eLineas->setMarca($marca);
                $eLineas->setModelo($modelo);
                $eLineas->setArticulo($articulo);
                $eLineas->setCantidad($cantidad);

                $entityManager = $this->getDoctrine()->getManager();

                $entityManager->persist($eLineas);
                $entityManager->flush();



                if( $c1 % 3 == 0){$c1=1;}
                else{$c1++;}


            }


            return $this->redirect("/orden/".$orden."");
        }



        ///////////////////formulario editar cabecera
        $formularioCabecera = new ECabecera();

        $formularioCabecera = $this->createFormBuilder($formularioCabecera)
            ->add('fecha', DateType::class)
            ->add('destino', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Siguiente'))
            ->getForm();

        $formularioCabecera->handleRequest($request);

        if ($formularioCabecera->isSubmitted() && $formularioCabecera->isValid()) {

            $cabe1 = $formularioCabecera->getData();

            $cambio = $cabe1-> getDestino();
            $entityManager = $this->getDoctrine()->getManager();


            $cabe = $entityManager->getRepository(ECabecera::class)->find($orden);


            $cabe->setDestino($cambio);
            $cabe->setFecha(new \DateTime());

            $entityManager->persist($cabe);
            $entityManager->flush();


            return $this->redirect("/orden/{$orden}");
        }


        //////formulario para quitar lineas


        $lineas = $this->getDoctrine()->getRepository(ELineas::class);
        $lineas = $lineas->findBy( ['orden' => $orden] );


        $formPedido = $this->createFormBuilder();


        foreach ($lineas as $a ) {


            $formPedido->add("btn_".$a->getId(), SubmitType::class, array('label' => 'Eliminar línea'));

        }

        $formPedido = $formPedido->getForm();

        $formPedido->handleRequest($request);


        $repCabecera = $this->getDoctrine()->getRepository(ECabecera::class);
        $cabe = $repCabecera->find($orden);

        return $this->render('entrega/entr_linea.html.twig', [
                'formulario' => $formulario->createView(),
                'formularioCabecera' => $formularioCabecera->createView(),
                'formPedido' => $formPedido->createView(),
                'listaArticulo' => $listaArticulos,
                'cabe' => $cabe,
                'lineas' => $lineas



            ]);


    }






}
