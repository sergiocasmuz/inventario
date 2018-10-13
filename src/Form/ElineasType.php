<?php

namespace App\Form;

use App\Entity\ELineas;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ElineasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder
            ->add('idArticulo', HiddenType::class)
            ->add('orden', HiddenType::class)
            ->add('articulo', HiddenType::class)
            ->add('marca', HiddenType::class)
            ->add('familia', HiddenType::class)
            ->add('modelo', HiddenType::class)
            ->add('nroSerie', TextType::class, array('label' => ' '))
            ->add('cantidad', HiddenType::class)
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
