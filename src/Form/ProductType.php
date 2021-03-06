<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Base;
use App\Form\PlaceType;
use App\Form\SourceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Наименование товара'])
            ->add('sources', CollectionType::class, [
             'entry_type' => SourceType::class,
             'allow_add'  => true,
             'allow_delete'  => true,
             'prototype'  => true,
             'by_reference' => false
            ])
            ->add('price', TextType::class, ['label' => 'Цена'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
