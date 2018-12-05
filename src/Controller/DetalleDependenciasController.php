<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\DBAL\Driver\Connection;

class DetalleDependenciasController extends AbstractController
{
    /**
     * @Route("/detalle/dependencias/{dependencia}", name="detalle_dependencias")
     */
    public function index(Connection $connection, $dependencia)
    {

      $sql_3 = "SELECT *  from ECabecera EC left join ELineas EL on EC.id = EL.orden WHERE destino = ?";
      $stmt_3 = $connection->prepare($sql_3);
      $stmt_3 -> bindValue(1,$dependencia);
      $stmt_3 -> execute();
      $detalles = $stmt_3 -> fetchAll();


        return $this->render('detalle_dependencias/index.html.twig', [
            'dependencia' => $detalles,
        ]);
    }
}
