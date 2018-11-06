<?php

namespace App\Controller;

use App\Entity\Articulos;
use App\Entity\stock;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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

    public function nuevo(Request $request)
    {

        $articulos = new Articulos();

        $formulario = $this->createFormBuilder($articulos);
        $formulario->add('familia',TextType::class);
        $formulario->add('articulo',TextType::class);
        $formulario->add('marca',TextType::class);
        $formulario->add('modelo',TextType::class);
        $formulario->add('detalle',TextType::class);
        $formulario->add('save', SubmitType::class, array('label' => 'Guardar'));
        $formulario = $formulario->getForm();

        $formulario = $formulario->handleRequest($request);

        if ($formulario->isSubmitted() && $formulario->isValid()) {


            $em = $this -> getDoctrine() -> getManager();

            ///////////////ingresar aticulo
            $tablaArt = $em ->getRepository(Articulos::class);
            $art = $formulario -> getData();

            $em->persist($art);
            $em->flush();

            $idArt = $art->getId();
            $familia = $art-> getFamilia();
            $articulo = $art-> getArticulo();
            $marca = $art-> getMarca();
            $modelo = $art-> getModelo();
            $detalle = $art-> getDetalle();


            ///////////////ingresar stock
            $tablaArt = $em ->getRepository(stock::class);

            $stock = new stock();

            $stock -> setFamilia($familia);
            $stock -> setArticulo($articulo);
            $stock -> setMarca($marca);
            $stock -> setModelo($modelo);
            $stock -> setDetalle($detalle);
            $stock -> setCantidad(0);
            $stock -> setIdArticulo($idArt);

            $em->persist($stock);
            $em->flush();

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
