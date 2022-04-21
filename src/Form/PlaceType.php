<?php

namespace App\Form;

use App\Entity\Place;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PlaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Наименование области'])
            ->add('width', TextType::class, ['label' => 'Ширина области'])
            ->add('height', TextType::class, ['label' => 'Высота области'])
            ->add('image', VichImageType::class, [
                 'label' => 'Фото',
                 'required' => false,
                 'download_label' => 'Скачать файл',
                 'allow_delete' => false,
                 'delete_label' => 'Удалить файл'
                 ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Place::class,
        ]);
    }
}
