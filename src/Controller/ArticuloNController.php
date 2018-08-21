<?php

namespace App\Controller;

use App\Entity\Articulos;

use function PHPSTORM_META\type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


use Symfony\Component\Routing\Annotation\Route;

class ArticuloNController extends AbstractController
{
    /**
     * @Route("/articulo/n", name="articulo_n")
     */

    public function new(Request $request)
    {

        $art = new Articulos();

        $formulario = $this->createFormBuilder($art)
            ->add('fecha', DateType::class)
            ->add('nroSerie', IntegerType::class)
            ->add('codBarra', IntegerType::class)
            ->add('familia',TextType::class)
            ->add('articulo',TextType::class)
            ->add('marca',TextType::class)
            ->add('modelo',TextType::class)
            ->add('detalle',TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();

        $formulario->handleRequest($request);

        if ($formulario->isSubmitted() && $formulario->isValid()) {
            // $formulario->getData()

            $art = $formulario->getData();


             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($art);
             $entityManager->flush();

            return $this->render('articulo_n/art.html.twig', [
                'formulario' => $formulario->createView(),
            ]);
        }

        return $this->render('articulo_n/art.html.twig', [
            'formulario' => $formulario->createView(),
        ]);
    }
}
