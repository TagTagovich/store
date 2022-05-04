<?php

namespace App\Form;

use App\Entity\Base;
use App\Entity\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BaseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Наименование базы'])
            ->add('price', TextType::class, ['label' => 'Цена'])
            ->add('places', CollectionType::class, [
             'label' => false,
             'entry_type' => PlaceType::class,
             'allow_add'  => true,
             'allow_delete'  => true,
             'prototype'  => true,
             'by_reference' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Base::class,
        ]);
    }
}
