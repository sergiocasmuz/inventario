<?php

namespace App\Controller;

use App\Entity\Articulos;
use App\Entity\ECabecera;
use App\Entity\NrosIdentificacion;
use App\Entity\ELineas;
use App\Entity\stock;
use App\Form\ElineasType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityRepository;

class EAgregarController extends AbstractController
{
    /**
     * @Route("/agregar/articulos/{orden}", name="articulos_articulos")
     */

    public function linea(Request $request ,$orden)
    {
              $validacion = 0;
              $em = $this -> getDoctrine() -> getManager();
              $ecabe = $em -> getRepository(ECabecera::class) -> find($orden);
              if($ecabe->getEstado() == 0 || $ecabe->getEstado() == 1 || $ecabe->getEstado() == 3 ){$nulo = false; }else{ $nulo = true;}  ///////corrobora el estado  2 = aprobado

              $formBuscar = $this -> createFormBuilder()
              ->add('buscar', TextType::class)
              ->add('ok', SubmitType::class)
              ->getForm()
              ->handleRequest($request);

              if ( $formBuscar->isSubmitted() && $formBuscar->isValid() && $nulo == false) {

                  $forms = [];
                  $rta = $formBuscar -> getData();
                  $buscar = $em -> getRepository(NrosIdentificacion::class) -> findByNroArticulo($rta["buscar"]) ;
                  if(count($buscar) != 0 ){
                  $listaArticulos = $em -> getRepository(Articulos::class)->find($buscar[0]->getIdArticulo());

                  $stock_check = $em -> getRepository(stock::class) -> findByIdArticulo($listaArticulos->getId());

                  $cantidad = $stock_check[0]->getCantidad();

                  if($cantidad <= 0){
                                      $label = "Sin stock";
                                      $act = true;
                                      $etiqueta = "etiqueta";
                                    }
                  else{ $label = "Agregar linea";
                        $act = false;
                        $etiqueta = "btn btn-rojo";}

                  $form = $this-> createFormBuilder();
                  $form->add('idArticulo', HiddenType::class, array( 'label' => 'idArticulo', 'attr' => array('value' =>  $listaArticulos->getId()  )  ));
                  $form->add('articulo', HiddenType::class, array('label' => $listaArticulos->getArticulo(), 'attr' => array('value' =>  $listaArticulos->getArticulo()  )  ));
                  $form->add('familia', HiddenType::class, array('label' => $listaArticulos->getFamilia(), 'attr' =>array( 'value' => $listaArticulos->getFamilia() )  ));
                  $form->add('marca', HiddenType::class, array('label' => $listaArticulos->getMarca(), 'attr' =>array( 'value' => $listaArticulos->getMarca() )  ));
                  $form->add('modelo', HiddenType::class, array('label' => $listaArticulos->getModelo(), 'attr' => array('value' => $listaArticulos->getModelo() )  ));
                  $form->add('nroArticulo', TextType::class, array( 'attr' => array('autofocus' => 'autofocus' )  )  );
                  $form->add('save', SubmitType::class, array('label' =>$label, 'attr' => array('class' => $etiqueta, 'disabled'=> $act)));
                  $form = $form -> getForm();
                  $form = $form -> handleRequest($request);

                        $form = $form -> createView();
                        $forms[] = $form;

                      }else{  $validacion = 1; }
              }

              else{

                $forms = [];

                $listaArticulos = $em -> getRepository(Articulos::class)->findAll();
            
                foreach ($listaArticulos as $articulo) {

                  $stock_check = $em -> getRepository(stock::class) -> findByIdArticulo($articulo->getId());


                  $cantidad = $stock_check[0]->getCantidad();

                  if($cantidad <= 0){
                                      $label = "Sin stock";
                                      $act = true;
                                      $etiqueta = "etiqueta";
                                    }
                  else{ $label = "Agregar linea";
                        $act = false;
                        $etiqueta = "btn btn-rojo";}




                  $form = $this-> createFormBuilder();
                  $form->add('idArticulo', HiddenType::class, array( 'label' => 'idArticulo', 'attr' => array('value' =>  $articulo->getId()  )  ));
                  $form->add('articulo', HiddenType::class, array('label' => $articulo->getArticulo(), 'attr' => array('value' =>  $articulo->getArticulo()  )  ));
                  $form->add('familia', HiddenType::class, array('label' => $articulo->getFamilia(), 'attr' =>array( 'value' => $articulo->getFamilia() )  ));
                  $form->add('marca', HiddenType::class, array('label' => $articulo->getMarca(), 'attr' =>array( 'value' => $articulo->getMarca() )  ));
                  $form->add('modelo', HiddenType::class, array('label' => $articulo->getModelo(), 'attr' => array('value' => $articulo->getModelo() )  ));
                  $form->add('nroArticulo', TextType::class, array( 'attr' => array('autofocus' => 'autofocus' )  )  );
                  $form->add('save', SubmitType::class, array('label' =>$label, 'attr' => array('class' => $etiqueta, 'disabled'=> $act)));
                  $form = $form -> getForm();
                  $form = $form -> handleRequest($request);

          ///////////////////////////////////////////////////////////////////
                  if (   $form -> isSubmitted() && $form -> isValid()  && $nulo == false) {

                    $rtaBTN = $form -> getData();

                    $elineas = new ELineas();

                    $elineas->setOrden($orden);
                    $elineas->setIdArticulo($rtaBTN["idArticulo"]);
                    $elineas->setCantidad(1);
                    $elineas->setArticulo($rtaBTN["articulo"]);
                    $elineas->setMarca($rtaBTN["marca"]);
                    $elineas->setModelo($rtaBTN["modelo"]);
                    $elineas->setNroSerie($rtaBTN["nroArticulo"]);
                    $elineas->setFamilia($rtaBTN["familia"]);

                    $em -> persist($elineas);
                    $em -> flush();

                    return $this->redirect("/agregar/agregar/{$orden}");

                    }
          ///////////////////////////////////////////////////////////////////

                  $form = $form -> createView();
                  $forms[] = $form;

                  }

              }

              /* *************** FORMULARIO DE CABECERA ******************************** */

              $ecabe = $em -> getRepository(ECabecera::class) -> find($orden);

              if($ecabe->getEstado() == 0 || $ecabe->getEstado() == 3){$act = false; }else{ $act = true;}  ///////corrobora el estado  2 = aprobado

              $editarCabecera = $this->createFormBuilder();

              $editarCabecera ->add('nombreForm', HiddenType::class,array('attr' => array('value' => 'editarCabecera')));
              $editarCabecera ->add('fecha', DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd','attr' => array("value" => date("Y-m-d") )));
              $editarCabecera ->add('destino', TextType::class, array('attr' => array('value'=> $ecabe->getDestino())) );
              $editarCabecera ->add('recibe', TextType::class, array('attr' => array('value'=> $ecabe->getRecibe())));
              $editarCabecera ->add('legajo', TextType::class, array('attr' => array('value'=> $ecabe->getLegajo())));

              $editarCabecera ->add('save', SubmitType::class, array('label' => 'Siguiente', 'attr' => array("disabled" => $act)  ));
              $editarCabecera = $editarCabecera ->getForm();

              $editarCabecera->handleRequest($request);

              /* *************** RESPUESTA DE "FORMULARIO DE CABECERA" ***************** */

              if ( $editarCabecera->isSubmitted() && $editarCabecera->isValid() && $nulo == false ) {

                  $rta = $editarCabecera->getData();

                      $eManager = $this->getDoctrine()->getManager();
                      $ECabecera = $eManager->getRepository(ECabecera::class)->find($orden);

                      $ECabecera -> setFecha($rta["fecha"]);
                      $ECabecera -> setDestino($rta["destino"]);
                      $ECabecera -> setRecibe($rta["recibe"]);
                      $ECabecera -> setLegajo($rta["legajo"]);

                      $eManager->persist($ECabecera);
                      $eManager->flush();

                      ///////el nro de orden corresponde con el id de la cabecera
                      $orden = $ECabecera->getId();

                      return $this->redirect("/agregar/articulos/{$orden}");

              }

              $repCabecera = $this->getDoctrine()->getRepository(ECabecera::class);
              $cabe = $repCabecera->find($orden);


              /* ************ FORMULARIO CONFIRMAR ORDEN ******************************* */

              $formularioOrden = $this -> createFormBuilder();

              $formularioOrden -> add('nombreForm', HiddenType::class, array( 'attr' => array('value' => 'formularioOrden' ) ) );
              $formularioOrden -> add('envOrden', SubmitType::class, array( 'label' => 'Terminar orden', 'attr' => array('class' => 'btn btn-primary') ) );

              $formularioOrden = $formularioOrden -> getForm();
              $formularioOrden -> handleRequest($request);


              /* *************** RESPUESTA DE FORMULARIO CONFIRMAR ORDEN *************** */
              if ($formularioOrden->isSubmitted() && $formularioOrden->isValid() && $act == false ) {

                  $emLines = $this->getDoctrine() -> getManager();

                  $iCabe = $emLines -> getRepository(ECabecera::class)->find($orden);
                  $iCabe -> setEstado(1);
                  $em->flush();

                  $ilines = $emLines -> getRepository(ELineas::class)->findByOrden($orden);

                  return $this->redirect("/ordenEntrega");
              }


      $cabe = $em -> getRepository(ECabecera::class)->find($orden);
      $lineas = $this->getDoctrine()->getRepository(ELineas::class);
      $lineas = $lineas->findByOrden($orden);

      return $this->render('e_articulos/agregar.html.twig', [
          'orden' => $orden,
          'forms' => $forms,
          'cabecera' => $cabe,
          'lineas' => $lineas,
          'formBuscar' => $formBuscar -> createView(),
          'formularioOrden' => $formularioOrden -> createView(),
          'validacion' => $validacion

               ]);

    }

}
