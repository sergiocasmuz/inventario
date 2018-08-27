<?php

namespace App\Controller;
use App\Entity\Articulos;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;


class StockController extends AbstractController
{
	/**
     * @Route("/stock", name="stock")
     */

	public function list()
    {

        $repository = $this->getDoctrine()->getRepository(Articulos::class);

        $art = $repository->findAll();


        return $this->render('stock/stock.html.twig', ['art' => $art]);


}




    
}
