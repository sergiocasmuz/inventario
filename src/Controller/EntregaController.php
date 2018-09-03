<?php

namespace App\Controller;

use App\Entity\Articulos;
use App\Entity\ECabecera;
use App\Entity\ELineas;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
            $id_eCabecera = $articulo->getId();


            return $this->redirect("/orden/{$id_eCabecera}");
        }

        return $this->render('entrega/entr_cabecera.html.twig', ['formularioCabecera' => $formularioCabecera->createView()]);


    }


    /**
     * @Route("/orden/{id_eCabecera}", name="entrega")
     */

    public function linea(Request $request,$id_eCabecera,Request $request2)
    {

        $repository = $this->getDoctrine()->getRepository(Articulos::class);
        $listaArticulos = $repository->findAll();


        ///////////////////formulario de orden
        $formulario = $this->createFormBuilder();

        foreach($listaArticulos as $articulo ) {

            $idArt = $articulo->getId();

            $formulario->add($idArt, TextType::class);

        }

        $formulario->add('save', SubmitType::class, array('label' => 'Agregar Lineas'));

        $formulario = $formulario->getForm();

        $formulario->handleRequest($request);

        ////////////respuesta del formulario de articulos

        if ($formulario->isSubmitted() && $formulario->isValid()) {

            $respuesta = $formulario->getData();

            foreach ($respuesta as $id_articulo => $cantidad) {

                $eLineas = new ELineas();

                $eLineas->setIdECabecera($id_eCabecera);
                $eLineas->setIdArticulo($id_articulo);
                $eLineas->setCantidad($cantidad);

                $entityManager = $this->getDoctrine()->getManager();

                $entityManager->persist($eLineas);
                $entityManager->flush();


            }



            return $this->redirect("/orden/".$id_eCabecera."");
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


            $cabe = $entityManager->getRepository(ECabecera::class)->find($id_eCabecera);


            $cabe->setDestino($cambio);
            $cabe->setFecha(new \DateTime());

            $entityManager->persist($cabe);
            $entityManager->flush();


            return $this->redirect("/orden/{$id_eCabecera}");
        }


        $orden = $this->getDoctrine()->getRepository(ELineas::class);
        $orden = $orden->findBy(
                ['id_eCabecera' => $id_eCabecera]
            );




        $formPedido = $this->createFormBuilder();


        foreach ($orden as $a ) {

            $idAr = $a ->getIdArticulo();

            $pedidoList = $this->getDoctrine()->getRepository(Articulos::class);
            $rtaList = $pedidoList->find($idAr);

            $formPedido->add($rtaList->getMarca(), TextType::class);
            $formPedido->add('save', SubmitType::class, array('label' => 'Eliminar'));

        }

        $formPedido = $formPedido->getForm();

        $formPedido->handleRequest($request2);




        $repCabecera = $this->getDoctrine()->getRepository(ECabecera::class);
        $cabe = $repCabecera->find($id_eCabecera);



        return $this->render('entrega/entr_linea.html.twig', [
                'formulario' => $formulario->createView(),
                'formularioCabecera' => $formularioCabecera->createView(),
                'listaArticulo' => $listaArticulos,
                'cabe' => $cabe,
                'orden' => $orden,
                'formPedido' => $formPedido->createView()
            ]);


    }






}
