<?php

namespace App\Controller;

use App\Entity\Articulos;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticuloController extends AbstractController
{


    /**
     * @Route("/articulo/list", name="articulo_list")
     */
    public function list()
    {

        $repository = $this->getDoctrine()->getRepository(Articulos::class);

        $art = $repository->findAll();


        return $this->render('articulo/art_list.html.twig', ['art' => $art]);


}


    /**
     * @Route("/articulo/n", name="articulo_n")
     */

    public function new(Request $request)
    {

        $art = new Articulos();

        $formulario = $this->createFormBuilder($art)
            ->add('fecha', DateType::class)
            ->add('nroSerie', IntegerType::class)
            ->add('codBarra', IntegerType::class)
            ->add('familia',TextType::class)
            ->add('articulo',TextType::class)
            ->add('marca',TextType::class)
            ->add('modelo',TextType::class)
            ->add('detalle',TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();

        $formulario->handleRequest($request);

        if ($formulario->isSubmitted() && $formulario->isValid()) {
            // $formulario->getData()

            $art = $formulario->getData();


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($art);
            $entityManager->flush();

            return $this->render('articulo/art.html.twig', [
                'formulario' => $formulario->createView(),
            ]);
        }

        return $this->render('articulo/art.html.twig', [
            'formulario' => $formulario->createView(),
        ]);
    }

    /**
     * @Route("/articulo/editar/{id}", name="articulo_editar")
     */
    public function editar($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $art = $entityManager->getRepository(Articulos::class)->find($id);


        if (!$art) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $art->setMarca('HP');
        $entityManager->flush();

        return $this->render('articulo/art_list.html.twig', ['art' => $art]);

    }



    /**
     * @Route("/articulo/borrar/{id}", name="articulo_borrar")
     */
    public function borrar($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $art = $entityManager->getRepository(Articulos::class)->find($id);


        if (!$art) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $entityManager->remove($art);
        $entityManager->flush();

        return $this->render('articulo/art_list.html.twig', ['art' => $art]);

    }
    
    
}
