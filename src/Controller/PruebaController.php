<?php

namespace App\Controller;

use App\Form\PruebaFormType;
use App\Entity\ELineas;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class PruebaController extends AbstractController
{
    /**
     * @Route("/prueba", name="prueba")
     */
    public function index(Request $request)
    {



      $form = $this->createForm(PruebaFormType::class);

      $form =$form-> handleRequest($request);

      $rta = $form->getData();

      print_r($rta);

      $form = $form -> createView();

        return $this->render('prueba/index.html.twig', [
            'form' => $form,



        ]);
    }
}
