<?php

namespace App\Controller;

use Doctrine\ORM\EntityRepository;
use App\Entity\stock;
use App\Entity\Articulos;
use App\Entity\NrosIdentificacion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class StockController extends AbstractController
{
	/**
    * @Route("/stock", name="stock")
    */

	public function list(Request $request)
    {
				$error = 0;
				$em = $this -> getDoctrine() -> getManager();
				$repository = $this->getDoctrine() -> getManager();

        $art = $repository->getRepository(stock::class)->findAll();


				$formBusqueda = $this -> createFormBuilder()

				-> add('buscar', TextType::class)
			 	-> getForm()
       	-> handleRequest($request);

				if ($formBusqueda->isSubmitted() && $formBusqueda->isValid() ) {

					$rta = $formBusqueda -> getData();

					$nros = $em -> getRepository(NrosIdentificacion::class) -> findByNroArticulo($rta["buscar"]) ;
					if(count($nros) != 0){
															$error = "0";
															$art = $em -> getRepository(stock::class) -> findByIdArticulo($nros[0]->getIdArticulo());
														}
										else{
															$error = "1"; ////NO SE ENCONTRARON ARTICULOS	ARTICULOS
															$art = $repository->getRepository(stock::class)->findAll();
															}

				}



        return $this->render('stock/stock.html.twig', [
					'error' => $error,
					'art' => $art,
					'formBusqueda' => $formBusqueda -> createView()
				]);

}

	/**
	 * @Route("/stock/{orden}", name="stock_orden")
	 */

	public function list_filtro(Request $request, $orden)
		{
			$error = 0;
			$em = $this -> getDoctrine() -> getManager();
			$repository = $this->getDoctrine() -> getManager();

			$art = $repository->getRepository(stock::class)->findBy(array(), array( $orden => 'DESC' ));


			$formBusqueda = $this -> createFormBuilder()

			-> add('buscar', TextType::class)
			-> getForm()
			-> handleRequest($request);

			if ($formBusqueda->isSubmitted() && $formBusqueda->isValid() ) {

				$rta = $formBusqueda -> getData();

				$nros = $em -> getRepository(NrosIdentificacion::class) -> findByNroArticulo($rta["buscar"]) ;
				if(count($nros) != 0){
														$error = "0";
														$art = $em -> getRepository(stock::class) -> findByIdArticulo($nros[0]->getIdArticulo(), array( $orden => 'DESC'));
													}
									else{
														$error = "1"; ////NO SE ENCONTRARON ARTICULOS	ARTICULOS
														$art = $repository->getRepository(stock::class)->findBy(array(), array( $orden => 'DESC' ));
														}


			}

			return $this->render('stock/stock.html.twig', [
				'error' => $error,
				'art' => $art,
				'formBusqueda' => $formBusqueda -> createView()
			]);


}
}
