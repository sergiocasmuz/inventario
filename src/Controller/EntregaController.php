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

        $formulario = new ECabecera();

        $formulario = $this->createFormBuilder($formulario)
            ->add('fecha', DateType::class)
            ->add('destino', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Siguiente'))
            ->getForm();

        $formulario->handleRequest($request);

        if ($formulario->isSubmitted() && $formulario->isValid()) {

            $art = $formulario->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($art);
            $entityManager->flush();
            $id_eCabecera = $art->getId();


            return $this->redirect("/orden/{$id_eCabecera}");
        }

        return $this->render('entrega/entr_cabecera.html.twig', ['formulario' => $formulario->createView()]);


    }


    /**
     * @Route("/orden/{id_eCabecera}", name="entrega")
     */

    public function linea(Request $request,$id_eCabecera)
    {


        $repository = $this->getDoctrine()->getRepository(Articulos::class);
        $artList = $repository->findAll();


        ///////////////////formulario de orden
        $formulario = $this->createFormBuilder();

        foreach($artList as $articulo ) {

            $formulario->add($articulo->getId(), IntegerType::class);

        }

        $formulario->add('save', SubmitType::class, array('label' => 'Agregar Lineas'));

        $formulario = $formulario->getForm();

        $formulario->handleRequest($request);


        ////////////

        if ($formulario->isSubmitted() && $formulario->isValid()) {
            // $formulario->getData()

            $rta = $formulario->getData();

           // print_r($rta);


            foreach ($rta as $clave => $valor) {


                $eLineas = new ELineas();
                $eLineas->setIdECabecera($id_eCabecera);
                $eLineas->setIdArticulo($clave);
                $eLineas->setCantidad($valor);

                $entityManager = $this->getDoctrine()->getManager();

                $entityManager->persist($eLineas);
                $entityManager->flush();


            }



            return $this->redirect("/stock");
        }


        $repOrden = $this->getDoctrine()->getRepository(ELineas::class);
        $artOrden = $repOrden->findBy(
                ['id_eCabecera' => $id_eCabecera]
            );


        print_r($artOrden);






        $repCabecera = $this->getDoctrine()->getRepository(ECabecera::class);
        $cabe = $repCabecera->find($id_eCabecera);


        return $this->render('entrega/entr_linea.html.twig', [
                'formulario' => $formulario->createView(),
                'artList' => $artList,
                'cabe' => $cabe,
                'orden' => $artOrden
            ]);


    }






}
