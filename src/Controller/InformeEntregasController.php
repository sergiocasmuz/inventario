<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\ECabecera;
use App\Entity\ELineas;
use App\Entity\stock;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityRepository;


class InformeEntregasController extends AbstractController
{
    /**
     * @Route("/informe/entregas", name="informe_entregas")
     */
    public function index(Request $request)
    {

        $em = $this -> getDoctrine() ->  getManager();


        $desde = date("Y-11-1");
        $hasta = date("Y-11-31");

        $cabe = $em -> getRepository(ECabecera::class) -> findMes($desde,$hasta);

        $or = "";

        foreach ($cabe as $orden) {
          $nrOrden = $orden->getId();

          $elineas = $em -> getRepository(ELineas::class)->findByOrden($nrOrden);

          $canti = count($elineas);
          for($i = 0; $i < $canti; $i++){

            $or[$nrOrden]["orden"] = $nrOrden;
            $or[$nrOrden]["fecha"] = $orden -> getFecha();
            $or[$nrOrden]["destino"] = $orden -> getDestino();
            $or[$nrOrden]["recibe"] = $orden -> getRecibe();
            $or[$nrOrden]["lin"][$i]["articulo"] = $elineas[$i] -> getArticulo();
            $or[$nrOrden]["lin"][$i]["marca"] = $elineas[$i] -> getMarca();
            $or[$nrOrden]["lin"][$i]["familia"] = $elineas[$i] -> getFamilia();
            $or[$nrOrden]["lin"][$i]["modelo"] = $elineas[$i] -> getModelo();
            $or[$nrOrden]["lin"][$i]["nroSerie"] = $elineas[$i] -> getNroSerie();

          }


        }

        $formulario = $this -> createFormBuilder()

            -> add('desde', DateType::class)
            -> add('hasta', DateType::class)
            -> add('Consultar', SubmitType::class)
            -> getForm()
            -> handleRequest($request);

            if ($formulario->isSubmitted() && $formulario->isValid()) {  }

        return $this->render('informe_entregas/index.html.twig', [
            'formulario' => $formulario -> createView(),
            'orden' => $or
        ]);
    }
}
