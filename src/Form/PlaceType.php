<?php

namespace App\Form;

use App\Entity\Place;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PlaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Наименование области'])
            ->add('width', TextType::class)
            ->add('height', TextType::class)
            //->add('startX', TextType::class)
            //->add('startY', TextType::class)
            ->add('image', VichImageType::class, [
                 'label' => 'Фото',
                 'required' => false,
                 'download_label' => false,
                 'allow_delete' => false,
                 'delete_label' => 'Удалить файл'
                 ])
            //->add('dpi', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Place::class,
        ]);
    }
}
