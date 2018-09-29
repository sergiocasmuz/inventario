<?php

namespace App\Controller;

use Doctrine\ORM\EntityRepository;
use App\Entity\stock;
use App\Entity\NrosIdentificacion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


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


	/**
	 * @Route("/stock/{orden}", name="stock_orden")
	 */

	public function list_filtro($orden)
		{

			 $repository = $this->getDoctrine() -> getManager();

			$art = $repository->getRepository(stock::class) ->findBy(array(), array( $orden => 'DESC' )) ;



				return $this->render('stock/stock.html.twig', ['art' => $art]);
			}


			/**
			 * @Route("/stock/buscar/{buscar}", name="stock_filtro")
			 */


			public function list_buscar($buscar)
				{

					 $repository = $this->getDoctrine() -> getManager();
						$art = $repository -> getRepository(NrosIdentificacion::class) -> findArt('845973050573') ;

							print_r($art);

						return $this->render('stock/stock.html.twig', ['art' => $art]);
					}





}
