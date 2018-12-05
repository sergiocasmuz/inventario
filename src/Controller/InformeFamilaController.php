<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityRepository;

use App\Entity\ECabecera;
use App\Entity\ELineas;
use Doctrine\DBAL\Driver\Connection;


class InformeFamilaController extends AbstractController
{
    /**
     * @Route("/informe/famila", name="informe_famila")
     */
    public function index(Connection $connection)
    {

      $sql = "SELECT DISTINCT(destino) from ECabecera";

      $stmt = $connection->prepare($sql);
      $stmt->execute();
      $destinos = $stmt->fetchAll();




      foreach ($destinos as $destino) {

      $sql_2 = "SELECT familia, sum(cantidad) as suma from ECabecera EC left join ELineas EL on EC.id = EL.orden  where destino = ? group by familia";

      $stmt = $connection->prepare($sql_2);
      $stmt->bindValue(1, $destino["destino"]);
      $stmt->execute();
      $rta = $stmt->fetchAll();

      foreach ($rta as $key) {

      $dato[$destino["destino"]]["familia"] = $key["familia"];
      $dato[$destino["destino"]]["suma"] = $key["suma"];


      }


      }



        return $this->render('informe_famila/index.html.twig', [
            'array' => $dato,
        ]);
    }
}
