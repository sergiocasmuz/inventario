<?php

namespace App\Controller;

use App\Entity\stock;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


use Doctrine\DBAL\Driver\Connection;

class HomeController extends AbstractController
{
    /**
     * @Route("/estadisticas", name="home")
     */
    public function index(Connection $connection)
    {

        $em = $this -> getDoctrine() -> getManager();
        $art = $em -> getRepository(stock::class) -> findAll();

        $sql ="SELECT *, sum(cantidad) as total from stock stk group by familia";
        $sql2 ="SELECT *, sum(cantidad) as total from stock stk group by articulo";



        $stm = $connection -> prepare($sql);
        $stm2 = $connection -> prepare($sql2);


        $stm -> execute();
        $stm2 -> execute();
        $rta = $stm -> fetchAll();
        $rta2 = $stm2 -> fetchAll();

        foreach ($rta as $stk) {
          if($stk["total"] > 0){  $stkFamilias[$stk["familia"]] = $stk["total"];  }
        }

        foreach ($rta2 as $stk2) {
          if($stk2["total"] > 0){  $stkArticulos[$stk2["articulo"]] = $stk2["total"];  }
        }


        return $this->render('home/index.html.twig', [
          'familias' => $stkFamilias,
          'articulos' => $stkArticulos
        ]);
    }
}
