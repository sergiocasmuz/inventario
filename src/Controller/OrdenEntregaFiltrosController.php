<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class OrdenEntregaFiltrosController extends AbstractController
{
    /**
     * @Route("/orden/entrega/filtros{filtro}", name="orden_entrega_filtros")
     */
    public function index()
    {
        return $this->render('orden_entrega_filtros/index.html.twig', [
            'controller_name' => 'OrdenEntregaFiltrosController',
        ]);
    }
}
