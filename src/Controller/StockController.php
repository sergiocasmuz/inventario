<?php

namespace App\Controller;
use App\Entity\stock;
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

        $repository = $this->getDoctrine() -> getManager();

        $art = $repository->getRepository(stock::class)->findAll();



        return $this->render('stock/stock.html.twig', ['art' => $art]);


}




    
}
