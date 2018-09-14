<?php

namespace App\Controller;

use App\Entity\stock;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {

        $em = $this->getDoctrine() -> getManager();
        $art = $em->getRepository(stock::class)->findAll();

        $query = $em->createQuery("SELECT sum(s.cantidad) group by s.familia from App\Entity\stock s");

        $rtaQuery = $query->getResult();

        $flia = count($rtaQuery);

        print_r($rtaQuery);







        return $this->render('home/index.html.twig', ['art' => $art]);
    }
}
