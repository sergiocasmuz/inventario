<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\HttpFoundation\Request;

class ReporteBaseController extends AbstractController
{
    /**
     * @Route("/reporte/base", name="reporte_base")
     */
    public function index(Connection $connection, Request $request)
    {

        $em = $this-> getDoctrine() -> getManager();

        $formulario = $em -> createFormBuilder()
        -> add("desde", DateType::class)
        -> add("hasta", DateType::calss)
        -> add("Consultar", SubmitType)
        ->getForm()
        ->handleRequest();

        if ($formulario->isSubmitted() && $formulario->isValid()) {  }

        $i=0;

        $sql_1 = "SELECT destino from ECabecera order by destino desc";
        $stmt_1 = $connection -> prepare($sql_1);
        $stmt_1 -> execute();

        while ($destinos = $stmt_1 -> fetch()) {



                                                $sql_3 = "SELECT sum(cantidad) as total from ECabecera EC left join ELineas EL on EC.id = EL.orden  where destino = ?";
                                                $stmt_3 = $connection->prepare($sql_3);
                                                $stmt_3 -> bindValue(1, $destinos["destino"]);
                                                $stmt_3 -> execute();
                                                while ($row = $stmt_3 -> fetch()){

                                                  $dep[$destinos["destino"]]=$row["total"];

                                                  $i++;
                                                }

                                              }


        return $this->render('reporte_base/index.html.twig', [
            'array' => $dep,
        ]);
    }
}
