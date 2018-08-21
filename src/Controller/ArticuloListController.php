<?php

namespace App\Controller;

use App\Entity\Articulos;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ArticuloListController extends AbstractController
{
    /**
     * @Route("/articulo/list", name="articulo_list")
     */
    public function index()
    {

        $repository = $this->getDoctrine()->getRepository(Articulos::class);

        $art = $repository->findAll();


        return $this->render('articulo_n/art_list.html.twig', ['art' => $art]);


    }
}
